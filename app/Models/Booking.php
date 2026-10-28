<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    public const PENDING = 'pending';
    public const CONFIRMED = 'confirmed';
    public const CANCELLED = 'cancelled';
    public const EXPIRED = 'expired';

    protected $fillable = [
        'user_id', 'flight_id', 'reference', 'request_key', 'status',
        'seats', 'total_price', 'expires_at', 'confirmed_at',
    ];

    protected $casts = [
        'seats' => 'integer', 'total_price' => 'integer',
        'expires_at' => 'datetime', 'confirmed_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function flight(): BelongsTo { return $this->belongsTo(Flight::class); }
    public function passengers(): HasMany { return $this->hasMany(Passenger::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    public function holdsSeat(): bool
    {
        return $this->status === self::CONFIRMED
            || ($this->status === self::PENDING && $this->expires_at?->isFuture());
    }
}
