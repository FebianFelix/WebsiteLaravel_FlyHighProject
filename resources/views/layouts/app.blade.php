<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="FebianFelix — https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject">
    <meta name="application-name" content="FlyHigh by FebianFelix">
    <meta name="theme-color" content="#10263f">
    <meta name="description" content="{{ \App\Models\SiteSetting::valueFor('tagline') }}">
    <title>@yield('title', 'FlyHigh') · {{ \App\Models\SiteSetting::valueFor('brand_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('foto/logoflyhigh.png') }}">
    @unless(config('flyhigh.demo_mode'))<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @endunless
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
            <a class="{{ request()->routeIs('flights.*') ? 'active' : '' }}" href="{{ route('flights.index') }}">{{ __('ui.flights') }}</a>
            <a href="{{ route('about') }}">{{ __('ui.about') }}</a>
            <a href="{{ route('help') }}">{{ __('ui.help') }}</a><a href="{{ route('info') }}">{{ __('ui.information') }}</a><a href="{{ route('contact') }}">{{ __('ui.contact') }}</a>
            @auth
                <a href="{{ route('bookings.index') }}">{{ __('ui.bookings') }}</a><a href="{{ route('account.edit') }}">{{ __('ui.account') }}</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.portal') }}">Control Center</a>
                @endif
            @endauth
        </nav>
        <div class="nav-actions">
            @auth
                <span class="hello">Halo, {{ \Illuminate\Support\Str::limit(auth()->user()->name, 18) }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-ghost btn-small" type="submit">{{ __('ui.logout') }}</button></form>
            @else
                <a class="btn btn-ghost btn-small" href="{{ route('login') }}">{{ __('ui.login') }}</a>
                <a class="btn btn-primary btn-small" href="{{ route('register') }}">{{ __('ui.register') }}</a>
            @endauth
        </div>
        <details class="mobile-menu"><summary aria-label="Buka menu">☰</summary>
            <nav aria-label="Navigasi seluler">
                <a href="{{ route('home') }}">Home</a><a href="{{ route('flights.index') }}">{{ __('ui.flights') }}</a>
                <a href="{{ route('about') }}">{{ __('ui.about') }}</a><a href="{{ route('help') }}">{{ __('ui.help') }}</a><a href="{{ route('info') }}">{{ __('ui.information') }}</a><a href="{{ route('contact') }}">{{ __('ui.contact') }}</a>
                @auth <a href="{{ route('bookings.index') }}">{{ __('ui.bookings') }}</a> @if(auth()->user()->isAdmin())<a href="{{ route('admin.portal') }}">Control Center</a>@endif
                @else <a href="{{ route('login') }}">{{ __('ui.login') }}</a><a href="{{ route('register') }}">{{ __('ui.register') }}</a> @endauth
            </nav>
        </details>
    </div>
</header>
<main>@if(config('flyhigh.demo_mode'))<div class="site-banner">DEMO MODE · Offline SQLite · {{ __('ui.demo') }}</div>@endif @if(\App\Models\SiteSetting::valueFor('banner_message'))<div class="site-banner" role="status">{{ \App\Models\SiteSetting::valueFor('banner_message') }}</div>@endif
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
            <p>{{ \App\Models\SiteSetting::valueFor('tagline') }}</p><p class="muted">Booking management platform · Laravel v2.0.</p></div>
        <div><strong>Explore</strong><a href="{{ route('home') }}">Homepage</a><a href="{{ route('flights.index') }}">{{ __('ui.flights') }}</a><a href="{{ route('about') }}">{{ __('ui.about') }}</a><a href="{{ route('info') }}">Informasi perjalanan</a></div>
        <div><strong>Support</strong><a href="{{ route('help') }}">Help center & FAQ</a><a href="{{ route('contact') }}">Hubungi kami</a><a href="mailto:{{ \App\Models\SiteSetting::valueFor('support_email') }}">Email bantuan</a><a href="{{ route('legal') }}">Legal & privacy</a></div>
        <div><strong>Account & product</strong>@auth<a href="{{ route('bookings.index') }}">{{ __('ui.bookings') }}</a><a href="{{ route('account.edit') }}">Preferensi akun</a>@if(auth()->user()->isAdmin())<a href="{{ route('admin.portal') }}">Admin control center</a>@endif @else<a href="{{ route('login') }}">{{ __('ui.login') }}</a><a href="{{ route('register') }}">Buat akun</a>@endauth<p class="demo-note">DEMO: data dan tiket simulasi; tidak berlaku untuk perjalanan nyata.</p></div>
    </div>
    <div class="container footer-bottom"><span>{{ config('flyhigh.copyright') }} · FlyHigh v2.0</span><span><a href="{{ config('flyhigh.origin') }}" rel="noopener noreferrer" target="_blank">Original repository · FebianFelix</a> · <a href="{{ route('legal') }}">Terms</a></span></div>
</footer>
</body>
</html>
