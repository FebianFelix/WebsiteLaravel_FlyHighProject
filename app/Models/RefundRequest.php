<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = ['booking_id', 'user_id', 'reviewed_by', 'reason', 'status', 'amount', 'provider_reference'];
    protected $casts = ['amount' => 'integer'];
    public function booking() { return $this->belongsTo(Booking::class); }
    public function user() { return $this->belongsTo(User::class); }
}
