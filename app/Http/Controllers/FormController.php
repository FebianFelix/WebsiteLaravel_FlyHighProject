<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Tujuan;
use App\Models\TujuanAwal;
use App\Models\Maskapai;
use App\Models\KelasPenerbangan;
use App\Models\MetodePembayaran;


class FormController extends Controller
{
    public function showForm()
    {
        $kategori = Kategori::all();
        $tujuanAwal = TujuanAwal::with('kategori')->get();
        $tujuan = Tujuan::all();
        $maskapai = Maskapai::all();
        $kelasPenerbangan = KelasPenerbangan::all();
        $metodePembayaran = MetodePembayaran::all();
    
        return view('form', compact('kategori', 'tujuanAwal', 'tujuan', 'maskapai', 'kelasPenerbangan', 'metodePembayaran'));
    }
    
}
