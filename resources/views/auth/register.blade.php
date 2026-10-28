@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">JOIN FLYHIGH</span><h1>Perjalanan baru dimulai.</h1><p>Buat akun untuk menyimpan reservasi dan perjalananmu.</p>
    <form method="POST" action="{{ route('register') }}" class="stack-form">@csrf
        <label>Nama lengkap<input name="name" maxlength="100" value="{{ old('name') }}" autocomplete="name" required placeholder="Nama kamu"></label>
        <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required placeholder="nama@email.com"></label>
        <label>Password<input type="password" name="password" autocomplete="new-password" minlength="8" required placeholder="Minimal 8 karakter"></label>
        <label>Konfirmasi password<input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required placeholder="Ulangi password"></label>
        <button class="btn btn-primary btn-block" type="submit">Buat akun →</button>
    </form><p class="auth-bottom">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p></div></section>
@endsection
