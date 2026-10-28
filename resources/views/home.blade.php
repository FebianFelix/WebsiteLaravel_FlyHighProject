@extends('layouts.app')
@section('title', 'Penerbangan dibuat lebih sederhana')
@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy"><div class="eyebrow"><span class="dot"></span> FLYHIGH · TRAVEL REIMAGINED</div>
            <h1>Ke mana pun tujuanmu, <em>mulai dari sini.</em></h1>
            <p>Temukan penerbangan, bandingkan jadwal, dan kelola reservasi dalam satu tempat yang terasa effortless.</p>
            <div class="hero-trust"><span>✦ Pencarian cepat</span><span>✦ Harga transparan</span><span>✦ Reservasi mudah</span></div>
        </div>
        <div class="hero-art"><img src="{{ asset('foto/slide3.png') }}" alt="Inspirasi perjalanan FlyHigh"><div class="floating-label">Your next destination awaits <span>✈</span></div></div>
    </div>
</section>
<section class="search-overlap">
    <div class="container">
        <form class="search-panel" action="{{ route('flights.index') }}" method="GET">
            <div class="search-title"><span class="eyebrow">CARI PENERBANGAN</span><h2>Rencanakan perjalananmu</h2></div>
            <div class="search-fields">
                <label>Berangkat dari<input name="origin" placeholder="Contoh: Jakarta" maxlength="100" value="{{ request('origin') }}"></label>
                <label>Tujuan<input name="destination" placeholder="Contoh: Bali" maxlength="100" value="{{ request('destination') }}"></label>
                <label>Tanggal terbang<input type="date" name="date" min="{{ now()->toDateString() }}" value="{{ request('date') }}"></label>
                <button type="submit" class="btn btn-primary btn-search">Cari penerbangan →</button>
            </div>
            <p class="demo-note">Versi portfolio: jadwal dan harga merupakan data contoh, bukan ketersediaan tiket aktual.</p>
        </form>
    </div>
</section>
<section class="section container"><div class="section-heading"><div><span class="eyebrow">PILIHAN PERJALANAN</span><h2>Penerbangan mendatang</h2><p>Jelajahi inspirasi perjalanan untuk rencana berikutnya.</p></div><a href="{{ route('flights.index') }}" class="text-link">Lihat semua →</a></div>
    <div class="flight-grid">@forelse($flights as $flight)@include('components.flight-card', ['flight' => $flight])@empty
        <div class="empty-state wide"><h3>Belum ada jadwal penerbangan.</h3><p>Jalankan <code>php artisan db:seed</code> untuk menambahkan jadwal demo.</p></div>
    @endforelse</div>
</section>
<section class="section features-section"><div class="container"><div class="section-heading"><div><span class="eyebrow">MENGAPA FLYHIGH</span><h2>Travel planning, simplified.</h2></div></div>
    <div class="features-grid"><div class="feature"><span>⌕</span><h3>Cari lebih praktis</h3><p>Filter kota asal, tujuan, dan tanggal dengan mudah.</p></div><div class="feature"><span>◷</span><h3>Jadwal jelas</h3><p>Informasi keberangkatan, kedatangan, dan estimasi harga.</p></div><div class="feature"><span>▣</span><h3>Satu dashboard</h3><p>Lihat dan batalkan reservasi demo dari akunmu.</p></div></div>
</div></section>
@endsection
