<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tbl_ticket';
    public $timestamps = false;

    protected $fillable = [
        'penumpang_id', // Add penumpang_id to the fillable property
        // Add other fillable fields here if applicable
    ];

    public function penumpangs()
    {
        return $this->belongsToMany(Penumpang::class);
    }
}
