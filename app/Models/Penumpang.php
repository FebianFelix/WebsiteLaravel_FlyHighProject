<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    protected $table = 'tbl_penumpang';
    protected $primaryKey = 'penumpang_id';
    public $timestamps = false;

    public function penumpangs()
{
    return $this->belongsToMany(Penumpang::class);
}

}

