<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with('flight')->latest()->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Flight $flight): View
    {
        abort_unless($flight->is_active && $flight->departure_at->isFuture(), 404);

        return view('bookings.create', compact('flight'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'flight_id' => ['required', 'integer', 'exists:flights,id'],
            'passengers' => ['required', 'array', 'min:1', 'max:6'],
            'passengers.*' => ['required', 'string', 'max:120'],
        ]);

        $booking = DB::transaction(function () use ($data, $request) {
            $flight = Flight::query()->lockForUpdate()->findOrFail($data['flight_id']);
            $count = count($data['passengers']);

            if (! $flight->is_active || ! $flight->departure_at->isFuture()) {
                throw ValidationException::withMessages(['flight_id' => 'Penerbangan sudah tidak tersedia.']);
            }
            if ($flight->availableSeats() < $count) {
                throw ValidationException::withMessages(['passengers' => 'Kursi tersedia tidak mencukupi.']);
            }

            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'flight_id' => $flight->id,
                'reference' => 'FH-'.Str::upper(Str::random(12)),
                'status' => 'reserved',
                'seats' => $count,
                'total_price' => $flight->price * $count,
            ]);

            foreach ($data['passengers'] as $name) {
                $booking->passengers()->create(['full_name' => trim($name)]);
            }

            return $booking;
        }, 3);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Reservasi demo berhasil dibuat. Tidak ada pembayaran yang diproses.');
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
        $booking->load(['flight', 'passengers', 'user']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        DB::transaction(function () use ($booking) {
            $current = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            if ($current->status === 'reserved' && $current->flight->departure_at->isFuture()) {
                $current->update(['status' => 'cancelled']);
            }
        });

        return redirect()->route('bookings.show', $booking)->with('success', 'Status reservasi diperbarui.');
    }
}
