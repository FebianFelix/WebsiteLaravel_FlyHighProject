@extends('layouts.app')
@section('title', 'Verifikasi email')
@section('content')
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">ACCOUNT SECURITY</span><h1>Verifikasi emailmu.</h1><p>Kami telah mengirim tautan verifikasi. Periksa kotak masuk, atau kirim ulang email.</p>
@if(auth()->user()->hasVerifiedEmail())<div class="alert alert-success">Email sudah terverifikasi.</div>
@else<form method="POST" action="{{ route('verification.send') }}">@csrf<button type="submit" class="btn btn-primary btn-block">Kirim ulang email verifikasi</button></form>@endif
<p class="auth-bottom"><a href="{{ route('account.edit') }}">← Kembali ke akun</a></p></div></section>
@endsection
