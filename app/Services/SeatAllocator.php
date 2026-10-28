<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Flight;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SeatAllocator
{
    public function codes(Flight $flight): array
    {
        $codes = [];
        for ($i = 0; $i < $flight->seats_total; $i++) {
            $codes[] = (string) (intdiv($i, 6) + 1).chr(65 + ($i % 6));
        }
        return $codes;
    }

    public function occupied(Flight $flight): array
    {
        return BookingSeat::where('flight_id', $flight->id)->pluck('seat_code')->all();
    }

    public function assign(Flight $flight, Booking $booking, array $requested): void
    {
        // Called while a Flight row lock is held (BookingService).
        BookingSeat::where('flight_id', $flight->id)
            ->whereHas('booking', fn ($q) => $q->where('status', Booking::EXPIRED)
                ->orWhere('status', Booking::CANCELLED)
                ->orWhere(fn ($sub) => $sub->where('status', Booking::PENDING)->where('expires_at', '<=', now())))
            ->delete();
        $allowed = $this->codes($flight);
        $occupied = $this->occupied($flight);
        if ($requested) {
            if (count($requested) !== $booking->seats || count(array_unique($requested)) !== count($requested)) {
                throw ValidationException::withMessages(['seat_codes' => 'Pilih kursi unik sesuai jumlah penumpang.']);
            }
            foreach ($requested as $code) {
                if (! in_array($code, $allowed, true) || in_array($code, $occupied, true)) {
                    throw ValidationException::withMessages(['seat_codes' => 'Pilihan kursi tidak tersedia.']);
                }
            }
            $assigned = $requested;
        } else {
            $assigned = array_slice(array_values(array_diff($allowed, $occupied)), 0, $booking->seats);
        }
        if (count($assigned) !== $booking->seats) {
            throw ValidationException::withMessages(['seat_codes' => 'Kursi tersedia tidak mencukupi.']);
        }
        foreach ($assigned as $code) {
            $booking->seatAssignments()->create(['flight_id' => $flight->id, 'seat_code' => $code]);
        }
    }

    public function release(Booking $booking): void
    {
        $booking->seatAssignments()->delete();
    }
}
