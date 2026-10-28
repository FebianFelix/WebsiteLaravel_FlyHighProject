<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('account.profile', ['tokens' => $request->user()->tokens()->latest()->get()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
        ]);
        $user = $request->user();
        if ($user->email !== $data['email']) {
            $user->email_verified_at = null;
        }
        $user->fill($data)->save();

        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Profil diperbarui.');
    }

    public function password(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        $request->user()->forceFill(['password' => $data['password']])->save();
        $request->user()->tokens()->delete();
        return back()->with('success', 'Password diperbarui dan API token dicabut.');
    }

    public function createToken(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'current_password' => ['required', 'current_password'],
        ]);
        $token = $request->user()->createToken($data['name'], ['flights:read', 'bookings:read', 'bookings:write']);

        return back()->with('api_token_once', $token->plainTextToken);
    }

    public function deleteToken(Request $request, int $tokenId): RedirectResponse
    {
        $token = $request->user()->tokens()->findOrFail($tokenId);
        $token->delete();
        return back()->with('success', 'API token dicabut.');
    }
}
