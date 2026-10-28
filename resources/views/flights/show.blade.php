@extends('layouts.app')
@section('title', $flight->origin.' ke '.$flight->destination)
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('flights.index') }}">← Semua penerbangan</a><span class="eyebrow">DETAIL PENERBANGAN</span><h1>{{ $flight->origin }} <span class="accent">→</span> {{ $flight->destination }}</h1><p>{{ $flight->airline }} · {{ $flight->flight_number }}</p></div></section>
<section class="section container two-column">
    <div class="card detail-card"><div class="detail-header"><span class="airline-icon">✈</span><div><h2>{{ $flight->airline }}</h2><p>{{ $flight->flight_number }} · Jadwal demo</p></div></div>
        <div class="detail-route"><div><small>Berangkat</small><strong>{{ $flight->departure_at->format('H:i') }}</strong><span>{{ $flight->origin }}</span><small>{{ $flight->departure_at->format('d M Y') }}</small></div><div class="route-line">✈</div><div><small>Tiba</small><strong>{{ $flight->arrival_at->format('H:i') }}</strong><span>{{ $flight->destination }}</span><small>{{ $flight->arrival_at->format('d M Y') }}</small></div></div>
        <div class="divider"></div><p class="muted">Semua jadwal disediakan sebagai contoh portfolio. Reservasi ini tidak menghasilkan tiket maskapai sungguhan.</p>
    </div>
    <aside class="card summary-card"><span class="eyebrow">RINCIAN HARGA</span><h2>Ringkasan</h2><div class="summary-line"><span>Harga per penumpang</span><strong>Rp {{ number_format($flight->price, 0, ',', '.') }}</strong></div><div class="summary-line"><span>Kursi tersedia (demo)</span><strong>{{ $flight->availableSeats() }}</strong></div><div class="divider"></div><strong class="price-large">Rp {{ number_format($flight->price, 0, ',', '.') }}</strong><p class="muted">Per penumpang · tanpa pembayaran</p>
    @if($flight->availableSeats() > 0)<a class="btn btn-primary btn-block" href="{{ route('bookings.create', $flight) }}">Lanjut reservasi →</a>@else<span class="btn btn-ghost btn-block disabled">Kursi penuh</span>@endif
    </aside>
</section>
@endsection
