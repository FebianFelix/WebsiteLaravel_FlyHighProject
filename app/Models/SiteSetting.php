<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];

    public const DEFAULTS = [
        'brand_name' => 'FlyHigh',
        'tagline' => 'Your next journey starts here.',
        'support_email' => 'support@example.test',
        'support_hours' => 'Monday–Friday, 09:00–17:00',
        'banner_message' => '',
        'booking_enabled' => '1',
        'maintenance_notice' => '',
    ];

    public static function valueFor(string $key): string
    {
        abort_unless(array_key_exists($key, self::DEFAULTS), 400);
        return (string) (static::query()->whereKey($key)->value('value') ?? self::DEFAULTS[$key]);
    }
}
