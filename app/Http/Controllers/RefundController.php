<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RefundRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RefundController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        $data = $request->validate(['reason' => ['required', 'string', 'min:10', 'max:1000']]);
        if (! in_array($booking->status, [Booking::CONFIRMED, Booking::CANCELLED], true)
            || ! $booking->payments()->where('status', 'succeeded')->exists()) {
            throw ValidationException::withMessages(['refund' => 'Only paid bookings are eligible for review.']);
        }
        if (RefundRequest::where('booking_id', $booking->id)->exists()) {
            return back()->with('success', 'Your existing refund request is being reviewed.');
        }
        RefundRequest::create([
            'booking_id' => $booking->id, 'user_id' => $request->user()->id,
            'reason' => $data['reason'], 'status' => 'requested',
            'amount' => $booking->total_price,
        ]);
        return back()->with('success', 'Refund request submitted for manual review. No refund is guaranteed.');
    }
}
