@extends('layouts.app')
@section('title', 'Buat password baru')
@section('content')
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">SECURE YOUR ACCOUNT</span><h1>Password baru.</h1><p>Gunakan password yang kuat dan unik.</p>
<form method="POST" action="{{ route('password.update') }}" class="stack-form">@csrf
<input type="hidden" name="token" value="{{ $token }}">
<label>Email<input type="email" name="email" required autocomplete="email" value="{{ old('email', $email) }}"></label>
<label>Password baru<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
<label>Konfirmasi password<input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></label>
<button class="btn btn-primary btn-block" type="submit">Perbarui password →</button>
</form></div></section>
@endsection
