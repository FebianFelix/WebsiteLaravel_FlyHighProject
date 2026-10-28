<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ItineraryService
{
    public function __construct(private readonly BookingService $bookings) {}

    public function reserve(User $user, array $flightIds, array $passengers, string $key): Itinerary
    {
        return DB::transaction(function () use ($user, $flightIds, $passengers, $key) {
            $existing = Itinerary::where('user_id',$user->id)->where('request_key',$key)->first();
            if ($existing) return $existing;
            if (count($flightIds) < 2 || count($flightIds) > 4 || count(array_unique($flightIds)) !== count($flightIds)) {
                throw ValidationException::withMessages(['flight_ids'=>'Select 2–4 unique flight segments.']);
            }
            $flights = Flight::whereIn('id',$flightIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            if ($flights->count() !== count($flightIds)) {
                throw ValidationException::withMessages(['flight_ids'=>'Some selected flights no longer exist.']);
            }
            $previous = null;
            foreach ($flightIds as $id) {
                $flight = $flights->get($id);
                if (! $flight->is_active || $flight->departure_at->isPast()
                    || ($previous && (
                        strcasecmp($previous->destination, $flight->origin) !== 0
                        || $flight->departure_at->lessThanOrEqualTo($previous->arrival_at)
                    ))) {
                    throw ValidationException::withMessages(['flight_ids'=>'Segments must connect in order, depart after the prior arrival, and remain active.']);
                }
                $previous = $flight;
            }
            $itinerary = Itinerary::create([
                'user_id'=>$user->id,
                'reference'=>'IT-'.Str::upper(Str::random(12)),
                'request_key'=>$key,
                'status'=>'pending',
                'total_price'=>0,
            ]);
            $total = 0;
            foreach ($flightIds as $index=>$id) {
                $booking = $this->bookings->reserve($user,(int)$id,$passengers,(string)Str::uuid());
                $booking->update(['itinerary_id'=>$itinerary->id,'leg_number'=>$index+1]);
                $total += $booking->total_price;
            }
            $itinerary->update(['total_price'=>$total]);
            return $itinerary;
        }, 3);
    }

    public function confirmDemo(Itinerary $itinerary): Itinerary
    {
        return DB::transaction(function () use ($itinerary) {
            $current = Itinerary::whereKey($itinerary->id)->lockForUpdate()->firstOrFail();
            if ($current->status === 'confirmed') return $current;
            if ($current->status !== 'pending') {
                throw ValidationException::withMessages(['itinerary'=>'Itinerary cannot be confirmed.']);
            }
            foreach ($current->bookings()->with('flight')->get()->sortBy('flight_id') as $booking) {
                $this->bookings->settleDemo($booking);
            }
            $current->update(['status'=>'confirmed']);
            return $current;
        }, 3);
    }

    public function cancel(Itinerary $itinerary): Itinerary
    {
        return DB::transaction(function () use ($itinerary) {
            $current = Itinerary::whereKey($itinerary->id)->lockForUpdate()->firstOrFail();
            if ($current->status === 'cancelled') return $current;
            foreach ($current->bookings()->with('flight')->get() as $booking) {
                if (! in_array($booking->status,[Booking::PENDING,Booking::CONFIRMED],true) ||
                    ! $booking->flight->departure_at->isFuture()) {
                    throw ValidationException::withMessages(['itinerary'=>'At least one leg cannot be cancelled as a group.']);
                }
            }
            foreach ($current->bookings()->orderBy('flight_id')->get() as $booking) {
                $this->bookings->cancel($booking);
            }
            $current->update(['status'=>'cancelled']);
            return $current;
        }, 3);
    }
}
