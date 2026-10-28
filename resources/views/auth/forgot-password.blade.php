@extends('layouts.app')
@section('title', 'Reset password')
@section('content')
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">ACCOUNT RECOVERY</span><h1>Lupa password?</h1><p>Masukkan alamat email yang terhubung dengan akunmu.</p>
<form method="POST" action="{{ route('password.email') }}" class="stack-form">@csrf
<label>Email<input type="email" name="email" required autocomplete="email" value="{{ old('email') }}" placeholder="email@example.com"></label>
<button class="btn btn-primary btn-block" type="submit">Kirim tautan reset</button>
</form><p class="auth-bottom"><a href="{{ route('login') }}">← Kembali ke login</a></p></div></section>
@endsection
