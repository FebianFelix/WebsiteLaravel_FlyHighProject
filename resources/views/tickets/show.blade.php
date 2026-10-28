@extends('layouts.app')
@section('title', 'E-ticket '.$booking->reference)
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('bookings.show', $booking) }}">← Reservasi</a><span class="eyebrow">E-TICKET · SIMULASI</span><h1>Flight itinerary</h1><p>Dokumen perjalanan demonstrasi, bukan boarding pass resmi.</p></div></section>
<section class="section container">
<div class="card ticket-card"><div class="ticket-header"><div><span class="eyebrow">FLYHIGH · DEMO ITINERARY</span><h2>{{ $booking->reference }}</h2></div><span class="status status-active">CONFIRMED (DEMO)</span></div>
<div class="divider"></div><div class="detail-header"><span class="airline-icon">✈</span><div><h2>{{ $booking->flight->airline }}</h2><p>{{ $booking->flight->flight_number }}</p></div></div>
<div class="detail-route"><div><small>Departure</small><strong>{{ $booking->flight->departure_at->format('H:i') }}</strong><span>{{ $booking->flight->origin }}</span><small>{{ $booking->flight->departure_at->format('d M Y') }} UTC</small></div><div class="route-line">✈</div><div><small>Arrival</small><strong>{{ $booking->flight->arrival_at->format('H:i') }}</strong><span>{{ $booking->flight->destination }}</span><small>{{ $booking->flight->arrival_at->format('d M Y') }} UTC</small></div></div>
<div class="divider"></div><h3>Passengers</h3><ol class="passenger-list">@foreach($booking->passengers as $passenger)<li>{{ $passenger->full_name }}</li>@endforeach</ol>
<div class="ticket-verification"><div class="qr-box">{!! $qrSvg !!}</div><div><h3>Verifikasi dokumen demo</h3><p class="muted">Scan untuk melihat status reservasi demo pada FlyHigh. Kode ini bukan tiket maskapai.</p><a class="text-link" href="{{ $verificationUrl }}">Buka verifikasi →</a></div></div>
<p class="ticket-disclaimer">PORTFOLIO DEMO ONLY · NO AIRLINE TICKET ISSUED · © 2026 FebianFelix</p>
<div class="form-actions"><button class="btn btn-ghost" onclick="window.print()">Cetak</button><a class="btn btn-primary" href="{{ route('bookings.ticket.pdf', $booking) }}">Unduh PDF →</a></div>
</div></section>
@endsection
