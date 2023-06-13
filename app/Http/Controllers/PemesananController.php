<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\TujuanAwal;
use App\Models\Tujuan;
use App\Models\KelasPenerbangan;
use App\Models\MetodePembayaran;


class PemesananController extends Controller
{
    public function showForm()
    {
        $kategori = Kategori::all();
        $tujuanAwal = TujuanAwal::all();
        $tujuan = Tujuan::all();
        $kelasPenerbangan = KelasPenerbangan::all();
        $metodePembayaran = MetodePembayaran::all();
    
        return view('pemesanan.form', compact('kategori', 'tujuanAwal', 'tujuan', 'kelasPenerbangan', 'metodePembayaran'));
    }
    
}
