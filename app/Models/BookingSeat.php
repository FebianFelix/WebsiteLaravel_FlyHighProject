<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    protected $fillable = ['flight_id', 'seat_code'];
    public function booking() { return $this->belongsTo(Booking::class); }
}
