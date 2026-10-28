<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = ['code', 'type', 'value', 'max_uses', 'valid_from', 'valid_until', 'is_active'];
    protected $casts = ['value' => 'integer', 'max_uses' => 'integer', 'valid_from' => 'datetime', 'valid_until' => 'datetime', 'is_active' => 'boolean'];

    public function discount(int $fare): int
    {
        return min($fare, $this->type === 'percentage'
            ? (int) floor($fare * $this->value / 100)
            : $this->value);
    }
    public function isAvailable(): bool
    {
        if (! $this->is_active || $this->valid_from?->isFuture() || $this->valid_until?->isPast()) return false;
        if ($this->max_uses === null) return true;
        $reserved = Booking::where('promo_code', $this->code)->where(function ($q) {
            $q->where('status', Booking::CONFIRMED)
                ->orWhere(fn ($p) => $p->where('status', Booking::PENDING)->where('expires_at', '>', now()));
        })->count();
        return $reserved < $this->max_uses;
    }
}
