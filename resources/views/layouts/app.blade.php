<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="FebianFelix — https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject">
    <meta name="application-name" content="FlyHigh by FebianFelix">
    <meta name="theme-color" content="#10263f">
    <meta name="description" content="FlyHigh — prototype modern aplikasi reservasi penerbangan berbasis Laravel.">
    <title>@yield('title', 'FlyHigh') · FlyHigh</title>
    <link rel="icon" type="image/png" href="{{ asset('foto/logoflyhigh.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="site-header">
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}" aria-label="FlyHigh Home">
            <span class="brand-mark">✈</span><span>fly<span class="brand-accent">high.</span></span>
        </a>
        <nav class="desktop-nav" aria-label="Navigasi utama">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="{{ request()->routeIs('flights.*') ? 'active' : '' }}" href="{{ route('flights.index') }}">Penerbangan</a>
            <a href="{{ route('about') }}">Tentang</a>
            <a href="{{ route('contact') }}">Kontak</a>
            @auth
                <a href="{{ route('bookings.index') }}">Reservasi Saya</a><a href="{{ route('account.edit') }}">Akun</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
            @endauth
        </nav>
        <div class="nav-actions">
            @auth
                <span class="hello">Halo, {{ \Illuminate\Support\Str::limit(auth()->user()->name, 18) }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-ghost btn-small" type="submit">Keluar</button></form>
            @else
                <a class="btn btn-ghost btn-small" href="{{ route('login') }}">Masuk</a>
                <a class="btn btn-primary btn-small" href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
        <details class="mobile-menu"><summary aria-label="Buka menu">☰</summary>
            <nav aria-label="Navigasi seluler">
                <a href="{{ route('home') }}">Home</a><a href="{{ route('flights.index') }}">Penerbangan</a>
                <a href="{{ route('about') }}">Tentang</a><a href="{{ route('contact') }}">Kontak</a>
                @auth <a href="{{ route('bookings.index') }}">Reservasi Saya</a> @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}">Admin</a>@endif
                @else <a href="{{ route('login') }}">Masuk</a><a href="{{ route('register') }}">Daftar</a> @endauth
            </nav>
        </details>
    </div>
</header>
<main>
    @if(session('success'))
        <div class="container alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="container alert alert-error" role="alert">
            <strong>Periksa kembali input Anda.</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @yield('content')
</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div><a class="brand footer-brand" href="{{ route('home') }}"><span class="brand-mark">✈</span>fly<span class="brand-accent">high.</span></a>
            <p>Rancang perjalanan dengan lebih mudah.</p></div>
        <div><strong>Jelajahi</strong><a href="{{ route('flights.index') }}">Penerbangan</a><a href="{{ route('about') }}">Tentang FlyHigh</a></div>
        <div><strong>Bantuan</strong><a href="{{ route('contact') }}">Kontak</a><a href="{{ route('login') }}">Akun</a></div>
        <div><strong>Portfolio demo</strong><p>Data penerbangan simulasi. Tidak melakukan transaksi atau penerbitan tiket resmi.</p></div>
    </div>
    <div class="container footer-bottom"><span>{{ config('flyhigh.copyright') }} · FlyHigh</span><span><a href="{{ config('flyhigh.origin') }}" rel="noopener noreferrer" target="_blank">Original repository · FebianFelix</a></span></div>
</footer>
</body>
</html>
