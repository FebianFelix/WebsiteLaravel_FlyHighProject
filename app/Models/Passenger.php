<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
{
    protected $fillable = ['full_name'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
