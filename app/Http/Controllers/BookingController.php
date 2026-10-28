<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookings) {}

    public function index(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with('flight')->latest()->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    public function create(Flight $flight): View
    {
        abort_unless($flight->is_active && $flight->departure_at->isFuture(), 404);
        return view('bookings.create', [
            'flight' => $flight, 'requestKey' => (string) Str::uuid(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'flight_id' => ['required', 'integer', 'exists:flights,id'],
            'request_key' => ['nullable', 'uuid'],
            'passengers' => ['required', 'array', 'min:1', 'max:6'],
            'passengers.*' => ['required', 'string', 'max:120'],
        ]);

        $booking = $this->bookings->reserve(
            $request->user(), (int) $data['flight_id'], $data['passengers'],
            $data['request_key'] ?? (string) Str::uuid(),
        );

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Reservasi demo dibuat. Konfirmasikan simulasi pembayaran sebelum batas waktu.');
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->canMonitor(), 403);
        $booking->load(['flight', 'passengers', 'user', 'payments']);
        return view('bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->canMonitor(), 403);
        $this->bookings->cancel($booking);
        return redirect()->route('bookings.show', $booking)->with('success', 'Status reservasi diperbarui.');
    }
}
