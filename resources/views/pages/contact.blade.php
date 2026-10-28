@extends('layouts.app')
@section('title', 'Kontak')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">GET IN TOUCH</span><h1>Terhubung dengan FlyHigh.</h1><p>Pertanyaan tentang fitur dan demo aplikasi? Jelajahi dokumentasi proyek.</p></div></section>
<section class="section container"><div class="card about-card"><h2>Tentang demo ini</h2><p>FlyHigh merupakan aplikasi portfolio. Kami tidak menerima pembayaran, menerbitkan tiket maskapai, ataupun menyediakan dukungan perjalanan sungguhan.</p><a class="btn btn-primary" href="{{ route('about') }}">Pelajari proyek ini →</a></div></section>
@endsection
