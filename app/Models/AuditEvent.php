<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditEvent extends Model
{
    protected $fillable = ['actor_user_id', 'action', 'subject', 'details'];
    protected $casts = ['details' => 'array'];
    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_user_id'); }
}
