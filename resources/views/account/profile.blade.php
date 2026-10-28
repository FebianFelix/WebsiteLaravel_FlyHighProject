@extends('layouts.app')
@section('title', 'Pengaturan akun')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">MY ACCOUNT</span><h1>Pengaturan akun</h1><p>Kelola profil, password, dan akses API secara aman.</p></div></section>
<section class="section container two-column">
<div class="card form-card"><h2>Informasi profil</h2><p class="muted">Email baru harus diverifikasi kembali.</p>
<form method="POST" action="{{ route('account.update') }}" class="stack-form">@csrf @method('PATCH')
<label>Nama<input name="name" maxlength="100" required value="{{ old('name', auth()->user()->name) }}"></label>
<label>Email<input type="email" name="email" required value="{{ old('email', auth()->user()->email) }}"></label>
<p class="muted">Email: {{ auth()->user()->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum diverifikasi' }}.
@if(!auth()->user()->hasVerifiedEmail())<a class="text-link" href="{{ route('verification.notice') }}">Verifikasi sekarang →</a>@endif</p>
<button type="submit" class="btn btn-primary">Simpan profil</button></form>
<div class="divider"></div><h2>Ubah password</h2>
<form method="POST" action="{{ route('account.password') }}" class="stack-form">@csrf @method('PUT')
<label>Password sekarang<input type="password" name="current_password" required autocomplete="current-password"></label>
<label>Password baru<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
<label>Konfirmasi password<input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></label>
<button class="btn btn-outline" type="submit">Perbarui password</button></form></div>
<aside class="card summary-card"><span class="eyebrow">DEVELOPER ACCESS</span><h2>API tokens</h2><p class="muted">Token dapat mengakses data akun dan membuat reservasi demo. Simpan secara rahasia.</p>
@if(session('api_token_once'))<div class="alert alert-success"><strong>Token baru — hanya ditampilkan sekali:</strong><div style="overflow-wrap:anywhere;user-select:all;font-family:monospace">{{ session('api_token_once') }}</div></div>@endif
<form method="POST" action="{{ route('account.tokens.create') }}" class="stack-form">@csrf
<label>Nama token<input name="name" maxlength="80" required placeholder="Laptop development"></label>
<label>Konfirmasi password<input type="password" name="current_password" required autocomplete="current-password"></label>
<button type="submit" class="btn btn-primary btn-block">Buat token</button></form><div class="divider"></div><h3>Token aktif</h3>
@forelse($tokens as $token)<div class="summary-line"><div><strong>{{ $token->name }}</strong><small class="block">Dibuat {{ $token->created_at->format('d M Y') }}</small></div>
<form method="POST" action="{{ route('account.tokens.destroy', $token->id) }}">@csrf @method('DELETE')<button class="link-danger" type="submit">Cabut</button></form></div>
@empty<p class="muted">Belum ada personal access token.</p>@endforelse
</aside></section>
<section class="section container"><div class="card form-card"><span class="eyebrow">PERSONALIZATION</span><h2>Preferensi perjalanan</h2><p class="muted">Preferensi disimpan untuk akun. Harga masih ditampilkan dalam IDR.</p>
<form class="stack-form" method="POST" action="{{ route('account.preferences') }}">@csrf @method('PATCH')
<label>Bahasa<select name="locale"><option value="id" {{ auth()->user()->locale==='id'?'selected':'' }}>Indonesia</option><option value="en" {{ auth()->user()->locale==='en'?'selected':'' }}>English</option></select></label>
<label>Preferensi mata uang<select name="currency">@foreach(['IDR','USD','SGD'] as $currency)<option value="{{ $currency }}" {{ auth()->user()->currency===$currency?'selected':'' }}>{{ $currency }}</option>@endforeach</select></label>
<label>Zona waktu<select name="timezone">@foreach(['Asia/Jakarta','Asia/Singapore','UTC','Europe/London','America/New_York'] as $zone)<option value="{{ $zone }}" {{ auth()->user()->timezone===$zone?'selected':'' }}>{{ $zone }}</option>@endforeach</select></label>
<label>Preferensi kursi<select name="seat_preference">@foreach(['any'=>'Bebas','window'=>'Jendela','aisle'=>'Lorong'] as $value=>$label)<option value="{{ $value }}" {{ auth()->user()->seat_preference===$value?'selected':'' }}>{{ $label }}</option>@endforeach</select></label>
<label class="check-label"><input type="checkbox" name="email_updates" value="1" {{ auth()->user()->email_updates?'checked':'' }}>Terima pembaruan produk via email</label>
<button class="btn btn-primary" type="submit">Simpan preferensi</button><a class="btn btn-outline" href="{{ route('account.wishlist') }}">Penerbangan tersimpan →</a>
</form></div></section>
@endsection
