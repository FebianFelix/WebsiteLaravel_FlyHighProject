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
    public function reserve(User $user, int $flightId, array $passengers, string $requestKey, array $seatCodes = [], ?string $promoCode = null): Booking
    {
        return DB::transaction(function () use ($user, $flightId, $passengers, $requestKey, $seatCodes, $promoCode) {
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

            $fare = $flight->price * $seats;
            $promo = null;
            $discount = 0;
            if ($promoCode) {
                $normalized = strtoupper(trim($promoCode));
                $promo = \App\Models\PromoCode::where('code', $normalized)->lockForUpdate()->first();
                if (! $promo || ! $promo->isAvailable()) {
                    throw ValidationException::withMessages(['promo_code' => 'Kode promo tidak tersedia atau kuota habis.']);
                }
                $discount = $promo->discount($fare);
            }
            $tax = (int) floor(($fare - $discount) * max(0, min(100, (int) config('flyhigh.tax_percent', 0))) / 100);
            $booking = Booking::create([
                'user_id' => $user->id, 'flight_id' => $flight->id,
                'reference' => 'FH-'.Str::upper(Str::random(12)),
                'request_key' => $requestKey,
                'status' => Booking::PENDING, 'seats' => $seats,
                'base_fare' => $fare, 'discount_amount' => $discount,
                'tax_amount' => $tax, 'promo_code' => $promo?->code,
                'total_price' => $fare - $discount + $tax,
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

    public function settleStripe(Booking $booking, string $sessionId, ?string $paymentIntent, int $amount): Booking
    {
        return DB::transaction(function () use ($booking, $sessionId, $paymentIntent, $amount) {
            $flight = Flight::query()->lockForUpdate()->findOrFail($booking->flight_id);
            $current = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            if ($current->status === Booking::CONFIRMED && $current->stripe_session_id === $sessionId) {
                return $current;
            }
            if ($current->stripe_session_id !== $sessionId || $amount !== $current->total_price
                || $current->status !== Booking::PENDING || $current->expires_at?->isPast() || $flight->departure_at->isPast()) {
                throw ValidationException::withMessages(['payment' => 'Payment needs manual reconciliation; no ticket has been issued.']);
            }
            $current->payments()->firstOrCreate(
                ['provider_reference' => $sessionId],
                ['provider' => 'stripe', 'external_reference' => $paymentIntent,
                 'amount' => $amount, 'status' => 'succeeded']
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
