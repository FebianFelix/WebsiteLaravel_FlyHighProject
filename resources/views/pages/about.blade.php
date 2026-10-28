@extends('layouts.app')
@section('title', 'Tentang FlyHigh')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">OUR STORY</span><h1>Membangun pengalaman perjalanan yang lebih baik.</h1><p>FlyHigh adalah evolusi dari project kampus menjadi portfolio full-stack Laravel.</p></div></section>
<section class="section container two-column"><div><span class="eyebrow">THE PROJECT</span><h2>Dari tugas kuliah ke aplikasi yang lebih serius.</h2><p class="lead">FlyHigh memperagakan alur pencarian penerbangan, registrasi akun, reservasi, dashboard pengguna, dan administrasi dengan praktik pengembangan Laravel modern.</p><p>Versi ini menggunakan data simulasi. Tidak terhubung ke inventori maskapai, pemesanan berbayar, atau sistem boarding pass resmi.</p><a href="{{ route('flights.index') }}" class="btn btn-primary">Jelajahi demo →</a></div><div class="card about-card"><span class="airline-icon">✈</span><h3>Built with purpose</h3><p>Laravel · Blade · Eloquent · MySQL / SQLite · Automated tests</p><div class="divider"></div><p>Fokus: akses yang aman, alur yang konsisten, dan UI responsif.</p></div></section>
@endsection
