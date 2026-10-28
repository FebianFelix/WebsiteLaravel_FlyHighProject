@extends('layouts.app')
@section('title', 'Cari penerbangan')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">EXPLORE YOUR JOURNEY</span><h1>Temukan penerbangan</h1><p>Pilih jadwal dengan harga terbaik untuk perjalananmu.</p></div></section>
<section class="section container">
<form method="GET" action="{{ route('flights.index') }}" class="filter-panel">
<label>Asal<input name="origin" placeholder="Kota keberangkatan" maxlength="100" value="{{ $filters['origin'] ?? '' }}"></label>
<label>Tujuan<input name="destination" placeholder="Kota tujuan" maxlength="100" value="{{ $filters['destination'] ?? '' }}"></label>
<label>Tanggal<input name="date" type="date" value="{{ $filters['date'] ?? '' }}"></label>
<label>Jenis perjalanan<select name="trip_type"><option value="one_way">Sekali jalan</option><option value="round_trip" {{ ($filters['trip_type']??'')==='round_trip'?'selected':'' }}>Pulang pergi</option><option value="multi_city" {{ ($filters['trip_type']??'')==='multi_city'?'selected':'' }}>Multi-city (dua segmen)</option></select></label>
<label>Tanggal kembali<input type="date" name="return_date" value="{{ $filters['return_date']??'' }}"></label>
<label>Kota perantara<input name="via" value="{{ $filters['via']??'' }}" maxlength="100" placeholder="Untuk multi-city"></label>
<label>Tanggal segmen kedua<input name="via_date" type="date" value="{{ $filters['via_date']??'' }}"></label>
<label>Kelas kabin<select name="cabin_class"><option value="">Semua kelas</option>@foreach(['economy'=>'Economy','premium_economy'=>'Premium Economy','business'=>'Business','first'=>'First'] as $code=>$label)<option value="{{ $code }}" {{ ($filters['cabin_class']??'')===$code?'selected':'' }}>{{ $label }}</option>@endforeach</select></label>
<label>Minimum bagasi (kg)<input type="number" min="0" max="60" name="min_baggage" value="{{ $filters['min_baggage']??'' }}" placeholder="Opsional"></label>
<label>Maskapai<select name="airline"><option value="">Semua maskapai</option>@foreach($airlines as $airline)<option value="{{ $airline }}" {{ ($filters['airline'] ?? '') === $airline ? 'selected' : '' }}>{{ $airline }}</option>@endforeach</select></label>
<label>Harga maksimum (IDR)<input type="number" min="1000" name="max_price" placeholder="Opsional" value="{{ $filters['max_price'] ?? '' }}"></label>
<label>Urutkan<select name="sort"><option value="departure">Berangkat terdekat</option><option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>Harga termurah</option><option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>Harga tertinggi</option></select></label>
<button class="btn btn-primary">Cari penerbangan</button>
<a class="btn btn-ghost" href="{{ route('flights.index') }}">Reset</a>
</form>
<div class="notice-panel card"><strong>Multi-leg planner</strong><p>For return and multi-city trips, you can reserve connected segments together instead of booking each one individually.</p><a class="btn btn-outline btn-small" href="{{ route('itineraries.create') }}">Build a multi-leg itinerary →</a></div>
<div class="list-heading"><h2>{{ $flights->total() }} penerbangan ditemukan</h2><span>Harga dalam IDR · Jadwal simulasi · Waktu UTC</span></div>
<div class="flight-grid">@forelse($flights as $flight)@include('components.flight-card', ['flight' => $flight])@empty<div class="empty-state wide"><h3>Jadwal tidak ditemukan.</h3><p>Coba ubah kota, filter, atau tanggal.</p><a class="btn btn-outline" href="{{ route('flights.index') }}">Semua penerbangan</a></div>@endforelse</div>
@include('components.pagination', ['paginator' => $flights])
@if($secondLegFlights !== null)
<div class="section-heading"><div><span class="eyebrow">SECOND LEG</span><h2>{{ ($filters['trip_type']??'')==='round_trip' ? 'Penerbangan kembali' : 'Penerbangan lanjutan' }}</h2><p>Pesan masing-masing segmen secara terpisah; ini belum merupakan tiket multi-city terintegrasi.</p></div></div>
<div class="flight-grid">@forelse($secondLegFlights as $flight)@include('components.flight-card',['flight'=>$flight])@empty<div class="empty-state wide">Jadwal segmen kedua belum tersedia.</div>@endforelse</div>
@endif
</section>
@endsection
