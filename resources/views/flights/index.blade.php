@extends('layouts.app')
@section('title', 'Cari penerbangan')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">EXPLORE YOUR JOURNEY</span><h1>Temukan penerbangan</h1><p>Pilih jadwal yang paling sesuai dengan perjalananmu.</p></div></section>
<section class="section container">
    <form method="GET" action="{{ route('flights.index') }}" class="filter-panel">
        <label>Asal<input name="origin" placeholder="Kota keberangkatan" value="{{ $filters['origin'] ?? '' }}"></label>
        <label>Tujuan<input name="destination" placeholder="Kota tujuan" value="{{ $filters['destination'] ?? '' }}"></label>
        <label>Tanggal<input name="date" type="date" value="{{ $filters['date'] ?? '' }}"></label>
        <button class="btn btn-primary">Terapkan filter</button>
        <a class="btn btn-ghost" href="{{ route('flights.index') }}">Reset</a>
    </form>
    <div class="list-heading"><h2>{{ $flights->total() }} penerbangan ditemukan</h2><span>Harga dalam IDR · Jadwal simulasi</span></div>
    <div class="flight-grid">@forelse($flights as $flight)@include('components.flight-card', ['flight' => $flight])@empty<div class="empty-state wide"><h3>Jadwal tidak ditemukan.</h3><p>Coba ubah kota atau tanggal pencarian.</p><a class="btn btn-outline" href="{{ route('flights.index') }}">Lihat semua penerbangan</a></div>@endforelse</div>
    @include('components.pagination', ['paginator' => $flights])
</section>
@endsection
