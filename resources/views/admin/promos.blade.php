@extends('layouts.app')
@section('title','Promotion management')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.settings.index') }}">← Site settings</a><span class="eyebrow">HEPHAESTUS · COMMERCIAL CONFIGURATION</span><h1>Promosi & kupon</h1><p>Diskon untuk reservasi. Kuota pemakaian dihitung dari reservasi aktif dan terkonfirmasi.</p></div></section>
<section class="section container two-column"><div class="card form-card"><h2>Buat kode promo</h2><form method="POST" action="{{ route('admin.promos.store') }}" class="stack-form">@csrf
<label>Kode<input required name="code" maxlength="40" placeholder="WELCOME10"></label>
<label>Tipe<select name="type"><option value="percentage">Persentase</option><option value="fixed">Nominal IDR</option></select></label>
<label>Nilai<input type="number" name="value" min="1" required></label>
<label>Maksimum pemakaian<input type="number" name="max_uses" min="1" placeholder="Kosong = unlimited"></label>
<label>Berlaku mulai<input type="datetime-local" name="valid_from"></label>
<label>Berlaku sampai<input type="datetime-local" name="valid_until"></label>
<button type="submit" class="btn btn-primary">Buat promo</button></form></div>
<div class="card summary-card"><h2>Kode aktif / terdahulu</h2>
@forelse($promos as $promo)<div class="summary-line"><span><strong>{{ $promo->code }}</strong><small class="block">{{ $promo->type }} · {{ $promo->value }} {{ $promo->type==='percentage'?'%':'IDR' }}</small></span>
<form method="POST" action="{{ route('admin.promos.toggle',$promo) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-outline btn-small">{{ $promo->is_active?'Nonaktifkan':'Aktifkan' }}</button></form></div>
@empty<p class="muted">Belum ada promo.</p>@endforelse{{ $promos->links() }}</div></section>
@endsection
