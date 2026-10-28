@extends('layouts.app')
@section('title','Website configuration')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.portal') }}">← Control center</a><span class="eyebrow">HEPHAESTUS · CONFIGURATION</span><h1>Site settings</h1><p>Pengaturan konten publik saja. Kunci rahasia, database, dan integrasi pembayaran tetap dikelola melalui environment server.</p></div></section>
<section class="section container two-column">
<div class="card form-card"><form class="stack-form" method="POST" action="{{ route('admin.settings.update') }}">@csrf @method('PATCH')
@foreach(['brand_name'=>'Nama brand','tagline'=>'Tagline','support_email'=>'Email bantuan','support_hours'=>'Jam layanan','banner_message'=>'Pengumuman homepage','maintenance_notice'=>'Pesan informasi layanan'] as $key=>$label)
<label>{{ $label }}<input name="{{ $key }}" value="{{ old($key, $settings[$key]) }}" maxlength="{{ $key === 'brand_name' ? 60 : ($key === 'support_email' ? 160 : 240) }}"></label>
@endforeach
<label><span>Izinkan reservasi baru</span><select name="booking_enabled"><option value="1" {{ old('booking_enabled',$settings['booking_enabled'])==='1'?'selected':'' }}>Aktif</option><option value="0" {{ old('booking_enabled',$settings['booking_enabled'])==='0'?'selected':'' }}>Nonaktif</option></select></label>
<button class="btn btn-primary" type="submit">Simpan pengaturan</button></form></div>
<aside class="card summary-card"><h2>Management</h2><p>Kelola data dan halaman publik.</p><a class="btn btn-outline btn-block" href="{{ route('admin.flights.index') }}">Kelola penerbangan →</a><a class="btn btn-outline btn-block" href="{{ route('admin.pages.index') }}">CMS & halaman →</a><a class="btn btn-outline btn-block" href="{{ route('admin.promos.index') }}">Kupon & promo →</a>
@if(in_array(auth()->user()->role,['athena','admin'],true))<a class="btn btn-outline btn-block" href="{{ route('admin.users.index') }}">Manage admin roles →</a>@endif<p class="muted">Konfigurasi di sini tidak memberikan akses ke kredensial server atau pembayaran.</p></aside>
</section>
@endsection
