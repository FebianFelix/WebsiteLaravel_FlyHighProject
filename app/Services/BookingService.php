<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Flight row is locked before inventory operations. All booking state
     * changes must use the same lock order (flight -> booking) to prevent
     * double-selling and reduce deadlock risk on MySQL/PostgreSQL.
     */
    public function reserve(User $user, int $flightId, array $passengers, string $requestKey, array $seatCodes = []): Booking
    {
        return DB::transaction(function () use ($user, $flightId, $passengers, $requestKey, $seatCodes) {
            $flight = Flight::query()->lockForUpdate()->findOrFail($flightId);
            $existing = Booking::query()->where('user_id', $user->id)
                ->where('request_key', $requestKey)->first();
            if ($existing) {
                if ($existing->flight_id !== $flightId) {
                    throw ValidationException::withMessages(['request_key' => 'Kunci permintaan sudah digunakan untuk penerbangan berbeda.']);
                }
                return $existing;
            }
            if (! $flight->is_active || $flight->departure_at->isPast()) {
                throw ValidationException::withMessages(['flight_id' => 'Penerbangan sudah tidak tersedia.']);
            }
            $seats = count($passengers);
            if ($flight->availableSeats() < $seats) {
                throw ValidationException::withMessages(['passengers' => 'Kursi tersedia tidak mencukupi.']);
            }

            $booking = Booking::create([
                'user_id' => $user->id, 'flight_id' => $flight->id,
                'reference' => 'FH-'.Str::upper(Str::random(12)),
                'request_key' => $requestKey,
                'status' => Booking::PENDING, 'seats' => $seats,
                'total_price' => $flight->price * $seats,
                'expires_at' => now()->addMinutes((int) config('flyhigh.hold_minutes', 15)),
            ]);

            foreach ($passengers as $name) {
                $booking->passengers()->create(['full_name' => trim($name)]);
            }

            app(SeatAllocator::class)->assign($flight, $booking, $seatCodes);
            return $booking;
        }, 3);
    }

    public function settleDemo(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $flight = Flight::query()->lockForUpdate()->findOrFail($booking->flight_id);
            $current = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            if ($current->status === Booking::CONFIRMED) {
                return $current;
            }
            if ($current->status !== Booking::PENDING || $current->expires_at->isPast() || $flight->departure_at->isPast()) {
                throw ValidationException::withMessages(['payment' => 'Reservasi tidak dapat dikonfirmasi atau sudah kedaluwarsa.']);
            }
            $current->payments()->firstOrCreate(
                ['provider_reference' => 'DEMO-'.$current->reference],
                ['provider' => 'demo', 'amount' => $current->total_price, 'status' => 'succeeded']
            );
            $current->update(['status' => Booking::CONFIRMED, 'confirmed_at' => now()]);
            return $current;
        }, 3);
    }

    public function cancel(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            Flight::query()->lockForUpdate()->findOrFail($booking->flight_id);
            $current = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            if (in_array($current->status, [Booking::PENDING, Booking::CONFIRMED], true)
                && $current->flight->departure_at->isFuture()) {
                $current->update(['status' => Booking::CANCELLED]);
                app(SeatAllocator::class)->release($current);
            }
            return $current;
        }, 3);
    }

    public function expire(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            Flight::query()->lockForUpdate()->findOrFail($booking->flight_id);
            $current = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            if ($current->status === Booking::PENDING && $current->expires_at->isPast()) {
                $current->update(['status' => Booking::EXPIRED]);
                app(SeatAllocator::class)->release($current);
            }
        }, 3);
    }
}
