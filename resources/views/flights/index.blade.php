@extends('layouts.app')
@section('title', 'Cari penerbangan')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">EXPLORE YOUR JOURNEY</span><h1>Temukan penerbangan</h1><p>Pilih jadwal dengan harga terbaik untuk perjalananmu.</p></div></section>
<section class="section container">
<form method="GET" action="{{ route('flights.index') }}" class="filter-panel">
<label>Asal<input name="origin" placeholder="Kota keberangkatan" maxlength="100" value="{{ $filters['origin'] ?? '' }}"></label>
<label>Tujuan<input name="destination" placeholder="Kota tujuan" maxlength="100" value="{{ $filters['destination'] ?? '' }}"></label>
<label>Tanggal<input name="date" type="date" value="{{ $filters['date'] ?? '' }}"></label>
<label>Maskapai<select name="airline"><option value="">Semua maskapai</option>@foreach($airlines as $airline)<option value="{{ $airline }}" {{ ($filters['airline'] ?? '') === $airline ? 'selected' : '' }}>{{ $airline }}</option>@endforeach</select></label>
<label>Harga maksimum (IDR)<input type="number" min="1000" name="max_price" placeholder="Opsional" value="{{ $filters['max_price'] ?? '' }}"></label>
<label>Urutkan<select name="sort"><option value="departure">Berangkat terdekat</option><option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>Harga termurah</option><option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>Harga tertinggi</option></select></label>
<button class="btn btn-primary">Cari penerbangan</button>
<a class="btn btn-ghost" href="{{ route('flights.index') }}">Reset</a>
</form>
<div class="list-heading"><h2>{{ $flights->total() }} penerbangan ditemukan</h2><span>Harga dalam IDR · Jadwal simulasi · Waktu UTC</span></div>
<div class="flight-grid">@forelse($flights as $flight)@include('components.flight-card', ['flight' => $flight])@empty<div class="empty-state wide"><h3>Jadwal tidak ditemukan.</h3><p>Coba ubah kota, filter, atau tanggal.</p><a class="btn btn-outline" href="{{ route('flights.index') }}">Semua penerbangan</a></div>@endforelse</div>
@include('components.pagination', ['paginator' => $flights])
</section>
@endsection
