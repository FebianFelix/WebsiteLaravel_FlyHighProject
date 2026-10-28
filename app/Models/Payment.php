<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['provider', 'provider_reference', 'status', 'amount', 'external_reference'];
    protected $casts = ['amount' => 'integer'];
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
