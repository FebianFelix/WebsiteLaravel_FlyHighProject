<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
    protected $table = 'tbl_tujuan';

    public function tujuanAwal()
    {
        return $this->hasMany(TujuanAwal::class, 'tujuan_id');
    }
}
