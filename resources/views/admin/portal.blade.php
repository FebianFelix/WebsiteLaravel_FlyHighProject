@extends('layouts.app')
@section('title', 'Administration workspace')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">FLYHIGH · ADMINISTRATOR WORKSPACE</span><h1>Control center</h1><p>{{ auth()->user()->roleLabel() }} · Pilih area sesuai izin akun. Semua aksi administratif dicatat.</p></div></section>
<section class="section container"><div class="admin-portal-grid">
@if(auth()->user()->canManageConfig())
<a class="card portal-tile" href="{{ route('admin.settings.index') }}"><span class="portal-icon">⚙</span><span class="eyebrow">HEPHAESTUS · CONFIGURATION</span><h2>Configuration & management</h2><p>Brand, CMS, pengaturan publik, promosi, dan inventori penerbangan. Akses hanya untuk Athena dan Hephaestus.</p><strong>Masuk ke konfigurasi →</strong></a>
@endif
@if(auth()->user()->canMonitor())
<a class="card portal-tile" href="{{ route('admin.dashboard') }}"><span class="portal-icon">◉</span><span class="eyebrow">ARGUS · MONITORING</span><h2>Dashboard & monitoring</h2><p>Aktivitas reservasi, laporan, status transaksi, dan pemantauan operasional. Akses hanya untuk Athena dan Argus.</p><strong>Buka monitoring →</strong></a>
@endif
</div>@if(in_array(auth()->user()->role,['athena','admin'],true))<div class="card notice-panel"><h3>Athena · Identity administration</h3><p>Manage employee roles and review administrator permissions.</p><a class="btn btn-outline btn-small" href="{{ route('admin.users.index') }}">Manage users & roles →</a></div>@endif
<div class="card notice-panel"><strong>Role separation</strong><p>Athena mengakses kedua area. Hephaestus khusus konfigurasi. Argus khusus pemantauan. Menu disembunyikan berdasarkan izin dan seluruh endpoint juga dilindungi di backend.</p></div></section>
@endsection
