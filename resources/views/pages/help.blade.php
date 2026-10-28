@extends('layouts.app')
@section('title','Help center')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">HELP CENTER</span><h1>How can we help?</h1><p>Panduan singkat mencari penerbangan, membuat reservasi, dan mengelola akun.</p></div></section>
<section class="section container"><div class="faq-grid">
@foreach([['Bagaimana cara mencari penerbangan?','Gunakan halaman Penerbangan. Anda bisa mencari tanpa akun dan memilih jadwal yang sesuai.'],['Apakah saya perlu login?','Tidak untuk menjelajah. Akun diperlukan saat membuat dan mengelola reservasi.'],['Apakah ini tiket asli?','Tidak. Jadwal, harga, dan tiket dalam demo adalah data simulasi, bukan tiket penerbangan resmi.'],['Bagaimana konfirmasi reservasi demo?','Buka Reservasi Saya, pilih booking yang masih aktif, lalu gunakan tombol pembayaran simulasi jika diaktifkan.'],['Bagaimana membatalkan reservasi?','Buka detail reservasi dan pilih Batalkan jika jadwal belum berangkat.'],['Bagaimana mengubah bahasa dan preferensi?','Masuk ke halaman Akun lalu simpan bahasa, zona waktu, mata uang dan preferensi kursi.']] as [$q,$a])
<details class="card faq-item"><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
@endforeach</div><div class="card notice-panel"><h2>Perlu bantuan lain?</h2><p>Hubungi {{ \App\Models\SiteSetting::valueFor('support_email') }} selama {{ \App\Models\SiteSetting::valueFor('support_hours') }}.</p><a class="btn btn-outline" href="{{ route('contact') }}">Kontak →</a></div></section>
@endsection
