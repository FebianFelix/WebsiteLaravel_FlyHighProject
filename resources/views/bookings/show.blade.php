@extends('layouts.app')
@section('title', 'Reservasi '.$booking->reference)
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('bookings.index') }}">← Reservasi saya</a><span class="eyebrow">BOOKING DETAILS</span><h1>Reservasi {{ $booking->reference }}</h1><p>Ringkasan perjalanan dan status pemesananmu.</p></div></section>
<section class="section container two-column">
    <div class="card detail-card"><div class="detail-header"><span class="airline-icon">✈</span><div><h2>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}</h2><p>{{ $booking->flight->airline }} · {{ $booking->flight->flight_number }}</p></div></div>
        <span class="status {{ $booking->status === 'reserved' ? 'status-active' : 'status-cancelled' }}">{{ $booking->status === 'reserved' ? 'Reserved (demo)' : 'Cancelled' }}</span>
        <div class="detail-route"><div><small>Keberangkatan</small><strong>{{ $booking->flight->departure_at->format('H:i') }}</strong><span>{{ $booking->flight->origin }}</span><small>{{ $booking->flight->departure_at->format('d M Y') }}</small></div><div class="route-line">✈</div><div><small>Kedatangan</small><strong>{{ $booking->flight->arrival_at->format('H:i') }}</strong><span>{{ $booking->flight->destination }}</span><small>{{ $booking->flight->arrival_at->format('d M Y') }}</small></div></div>
        <div class="divider"></div><h3>Penumpang ({{ $booking->seats }})</h3><ol class="passenger-list">@foreach($booking->passengers as $passenger)<li>{{ $passenger->full_name }}</li>@endforeach</ol>
    </div>
    <aside class="card summary-card"><span class="eyebrow">RINCIAN RESERVASI</span><h2>Ringkasan</h2><div class="summary-line"><span>Kode booking</span><strong>{{ $booking->reference }}</strong></div><div class="summary-line"><span>Penumpang</span><strong>{{ $booking->seats }} orang</strong></div><div class="summary-line"><span>Status</span><strong>{{ ucfirst($booking->status) }}</strong></div><div class="divider"></div><strong class="price-large">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong><p class="muted">Nilai simulasi · belum ada transaksi pembayaran.</p>
        @if($booking->status === 'reserved' && $booking->flight->departure_at->isFuture())
            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Batalkan reservasi demo ini?')">@csrf @method('PATCH')<button class="btn btn-outline btn-block" type="submit">Batalkan reservasi</button></form>
        @endif
        <button class="btn btn-ghost btn-block" type="button" onclick="window.print()">Cetak ringkasan</button>
    </aside>
</section>
@endsection
