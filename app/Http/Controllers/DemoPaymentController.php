<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\BookingConfirmedNotification;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DemoPaymentController extends Controller
{
    public function __construct(private readonly BookingService $bookings) {}

    public function confirm(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless((bool) config('flyhigh.demo_payment_enabled'), 404);
        abort_unless($request->user()->id === $booking->user_id, 403);

        $wasConfirmed = $booking->status === Booking::CONFIRMED;
        $booking = $this->bookings->settleDemo($booking);
        if (! $wasConfirmed) {
            $request->user()->notify(new BookingConfirmedNotification($booking));
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Simulasi pembayaran berhasil; tidak ada uang yang ditransfer.');
    }
}
