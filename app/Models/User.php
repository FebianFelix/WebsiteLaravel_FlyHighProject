<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable;

    public const ATHENA = 'athena';
    public const HEPHAESTUS = 'hephaestus';
    public const ARGUS = 'argus';

    protected $fillable = ['name', 'email', 'password', 'locale', 'currency', 'timezone', 'email_updates', 'seat_preference'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime', 'password' => 'hashed', 'email_updates' => 'boolean',
    ];

    public function wishlistedFlights(): BelongsToMany { return $this->belongsToMany(Flight::class, 'flight_wishlists'); }
    public function itineraries(): HasMany { return $this->hasMany(Itinerary::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function isAdmin(): bool { return in_array($this->role, ['admin', self::ATHENA, self::HEPHAESTUS, self::ARGUS], true); }
    public function canManageConfig(): bool { return in_array($this->role, ['admin', self::ATHENA, self::HEPHAESTUS], true); }
    public function canMonitor(): bool { return in_array($this->role, ['admin', self::ATHENA, self::ARGUS], true); }
    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ATHENA, 'admin' => 'Athena · Super Administrator',
            self::HEPHAESTUS => 'Hephaestus · Configuration',
            self::ARGUS => 'Argus · Monitoring',
            default => 'Traveler',
        };
    }
}
