<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginForm(): View { return view('auth.login'); }
    public function registerForm(): View { return view('auth.register'); }
    public function forgotPasswordForm(): View { return view('auth.forgot-password'); }
    public function resetPasswordForm(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'], 'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended($request->user()->isAdmin() ? route('admin.dashboard') : route('dashboard'));
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        $user = User::create($data);
        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Periksa email untuk verifikasi.');
    }

    public function sendPasswordReset(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        PasswordBroker::sendResetLink($request->only('email'));
        // Generic response to avoid exposing which email addresses exist.
        return back()->with('success', 'Jika email terdaftar, petunjuk reset password telah dikirim.');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'], 'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $status = PasswordBroker::reset($data, function (User $user, string $password) {
            $user->forceFill([
                'password' => $password, 'remember_token' => Str::random(60),
            ])->save();
            $user->tokens()->delete();
        });

        return $status === PasswordBroker::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil diperbarui.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Berhasil keluar.');
    }
}
