<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasPenerbangan extends Model
{
    use HasFactory;

    protected $table = 'tbl_kelas_penerbangan';
    protected $primaryKey = 'kelas_id';
}
