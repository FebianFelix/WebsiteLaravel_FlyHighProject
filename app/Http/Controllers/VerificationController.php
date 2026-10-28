<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function notice(Request $request): View { return view('auth.verify-email'); }

    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        abort_unless($request->user()->getKey() === $id, 403);
        abort_unless(hash_equals($hash, sha1($request->user()->getEmailForVerification())), 403);
        if (! $request->user()->hasVerifiedEmail() && $request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi.');
    }

    public function resend(Request $request): RedirectResponse
    {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();
        }
        return back()->with('success', 'Jika belum terverifikasi, email baru telah dikirim.');
    }
}
