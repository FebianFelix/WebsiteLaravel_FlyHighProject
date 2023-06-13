<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanAwal extends Model
{
    protected $table = 'tbl_tujuan_awal';

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'tujuan_id', 'kategori_id');
    }

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class, 'tujuan_id');
    }
}
