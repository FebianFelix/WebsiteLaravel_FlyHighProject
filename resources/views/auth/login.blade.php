@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">WELCOME BACK</span><h1>Selamat datang kembali.</h1><p>Masuk untuk melihat dan mengelola reservasi FlyHigh.</p>
    @if(config('flyhigh.demo_mode'))<div class="alert alert-success">Offline demo accounts: athena@flyhigh.test, hephaestus@flyhigh.test, argus@flyhigh.test, traveler@flyhigh.test. Password: <strong>{{ env('FLYHIGH_DEMO_PASSWORD','DemoPass123!') }}</strong>. Never reuse these accounts in a public production deployment.</div>@endif
    <form method="POST" action="{{ route('login') }}" class="stack-form">@csrf
        <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus placeholder="nama@email.com"></label>
        <label>Password<input type="password" name="password" autocomplete="current-password" required placeholder="Password"></label>
        <label class="check-label"><input type="checkbox" name="remember" value="1"><span>Ingat saya</span></label>
        <button class="btn btn-primary btn-block" type="submit">Masuk →</button>
    </form><p class="auth-bottom"><a href="{{ route('password.request') }}">Lupa password?</a></p><p class="auth-bottom">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p></div></section>
@endsection
