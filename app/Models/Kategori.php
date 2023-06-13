<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'tbl_kategori';

    public function tujuanAwal()
    {
        return $this->hasMany(TujuanAwal::class, 'tujuan_id', 'kategori_id');
    }
}
