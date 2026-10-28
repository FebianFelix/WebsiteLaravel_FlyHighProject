<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    protected $fillable = [
        'flight_number', 'airline', 'origin', 'destination',
        'departure_at', 'arrival_at', 'price', 'seats_total', 'is_active',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
        'price' => 'integer',
        'seats_total' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reservedSeats(): int
    {
        return (int) $this->bookings()->where('status', 'reserved')->sum('seats');
    }

    public function availableSeats(): int
    {
        return max(0, $this->seats_total - $this->reservedSeats());
    }
}
