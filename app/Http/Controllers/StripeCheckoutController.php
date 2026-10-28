<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\StripeGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StripeCheckoutController extends Controller
{
    public function __construct(private readonly StripeGateway $stripe) {}

    public function checkout(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        if ($booking->status !== Booking::PENDING || $booking->expires_at?->isPast()) {
            throw ValidationException::withMessages(['payment' => 'Reservation expired or already processed.']);
        }
        if ($booking->stripe_session_id) {
            throw ValidationException::withMessages(['payment' => 'Checkout already initiated. Check your payment status or contact support.']);
        }
        $session = $this->stripe->createCheckout($booking->load('user'));
        DB::transaction(function () use ($booking, $session) {
            \App\Models\Flight::whereKey($booking->flight_id)->lockForUpdate()->firstOrFail();
            $current = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($current->status !== Booking::PENDING || $current->expires_at?->isPast() || $current->stripe_session_id) {
                throw ValidationException::withMessages(['payment' => 'The reservation changed during checkout. Contact support before paying.']);
            }
            // Stripe's shortest Checkout Session lifetime is 30 minutes.
            $current->update(['stripe_session_id' => $session['id'], 'expires_at' => now()->addMinutes(35)]);
        }, 3);
        return redirect()->away($session['url']);
    }
}
