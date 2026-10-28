@extends('layouts.app')
@section('title', 'Reservasi '.$booking->reference)
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('bookings.index') }}">← Reservasi saya</a><span class="eyebrow">BOOKING DETAILS · DEMO</span><h1>Reservasi {{ $booking->reference }}</h1><p>Ringkasan perjalanan, penumpang, dan status pembayaran simulasi.</p></div></section>
<section class="section container two-column">
<div class="card detail-card"><div class="detail-header"><span class="airline-icon">✈</span><div><h2>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}</h2><p>{{ $booking->flight->airline }} · {{ $booking->flight->flight_number }}</p></div></div>
<span class="status {{ $booking->status === 'confirmed' ? 'status-active' : ($booking->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">{{ ['pending' => 'Menunggu pembayaran demo', 'confirmed' => 'Terkonfirmasi (demo)', 'cancelled' => 'Dibatalkan', 'expired' => 'Kedaluwarsa'][$booking->status] ?? $booking->status }}</span>
<div class="detail-route"><div><small>Berangkat</small><strong>{{ $booking->flight->departure_at->format('H:i') }}</strong><span>{{ $booking->flight->origin }}</span><small>{{ $booking->flight->departure_at->format('d M Y') }} UTC</small></div><div class="route-line">✈</div><div><small>Tiba</small><strong>{{ $booking->flight->arrival_at->format('H:i') }}</strong><span>{{ $booking->flight->destination }}</span><small>{{ $booking->flight->arrival_at->format('d M Y') }} UTC</small></div></div>
<div class="divider"></div><h3>Penumpang ({{ $booking->seats }})</h3>
<ol class="passenger-list">@foreach($booking->passengers as $passenger)<li>{{ $passenger->full_name }}</li>@endforeach</ol>
<p class="muted">Kursi dialokasikan: {{ $booking->seatAssignments->pluck('seat_code')->join(', ') ?: 'Tidak tersedia' }}</p>
<p class="muted">Dokumen ini hanya bukti reservasi simulasi. Bukan boarding pass atau tiket maskapai resmi.</p>
</div>
<aside class="card summary-card"><span class="eyebrow">PAYMENT SIMULATOR</span><h2>Ringkasan</h2>
<div class="summary-line"><span>Referensi</span><strong>{{ $booking->reference }}</strong></div>
<div class="summary-line"><span>Penumpang</span><strong>{{ $booking->seats }}</strong></div>
<div class="summary-line"><span>Status</span><strong>{{ ucfirst($booking->status) }}</strong></div>
@if($booking->status === 'pending')<div class="summary-line"><span>Batas konfirmasi</span><strong>{{ $booking->expires_at?->format('d M, H:i') }} UTC</strong></div>@endif
<div class="summary-line"><span>Harga dasar</span><strong>Rp {{ number_format($booking->base_fare ?: $booking->total_price, 0, ',', '.') }}</strong></div>
@if($booking->discount_amount)<div class="summary-line"><span>Promo {{ $booking->promo_code }}</span><strong>-Rp {{ number_format($booking->discount_amount,0,',','.') }}</strong></div>@endif
@if($booking->tax_amount)<div class="summary-line"><span>Simulasi pajak</span><strong>Rp {{ number_format($booking->tax_amount,0,',','.') }}</strong></div>@endif
<div class="divider"></div><strong class="price-large">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
<p class="muted">Nominal ilustratif; tidak ada pembayaran yang diproses.</p>
@if($booking->status === 'pending' && $booking->expires_at?->isFuture() && config('flyhigh.demo_payment_enabled'))
<form method="POST" action="{{ route('bookings.demo-payment', $booking) }}">@csrf
<button class="btn btn-primary btn-block" type="submit">Konfirmasi pembayaran DEMO →</button>
</form>@endif
<a class="btn btn-outline btn-block" href="{{ route('bookings.invoice',$booking) }}">Invoice / receipt →</a>
@if($booking->status==='pending' && config('flyhigh.payment_gateway')==='stripe')<form method="POST" action="{{ route('bookings.stripe-checkout',$booking) }}">@csrf<button class="btn btn-primary btn-block" type="submit">Bayar melalui Stripe Checkout</button></form>@endif
@if($booking->status==='confirmed' && !$booking->refundRequest)<form method="POST" action="{{ route('bookings.refund',$booking) }}">@csrf<label>Alasan permohonan refund<textarea name="reason" required maxlength="1000" minlength="10"></textarea></label><button class="btn btn-outline btn-block" type="submit">Ajukan refund</button></form>@endif
@if($booking->refundRequest)<p class="muted">Refund: {{ ucfirst($booking->refundRequest->status) }}</p>@endif
@if(in_array($booking->status, ['pending','confirmed']) && $booking->flight->departure_at->isFuture())
<form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Batalkan reservasi demo ini?')">@csrf @method('PATCH')<button class="btn btn-outline btn-block" type="submit">Batalkan reservasi</button></form>
@endif
@if($booking->status === 'confirmed')<a class="btn btn-outline btn-block" href="{{ route('bookings.ticket', $booking) }}">Buka e-ticket demo →</a>@endif
<button class="btn btn-ghost btn-block" type="button" onclick="window.print()">Cetak ringkasan</button>
</aside></section>
@endsection
