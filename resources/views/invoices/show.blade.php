@extends('layouts.app')
@section('title','Invoice '.$booking->reference)
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('bookings.show',$booking) }}">← Reservasi</a><span class="eyebrow">BOOKING DOCUMENT</span><h1>Invoice {{ $booking->reference }}</h1><p>{{ $booking->status==='confirmed' ? 'Receipt for booking confirmation' : 'Pro forma · Payment pending' }}</p></div></section>
<section class="section container"><div class="card content-article">
<h2>FlyHigh · {{ $booking->reference }}</h2><p>Issued {{ $booking->created_at->format('d M Y H:i') }} UTC · {{ $booking->user->name }}</p>
<p>{{ $booking->flight->flight_number }} · {{ $booking->flight->origin }} → {{ $booking->flight->destination }} · {{ $booking->flight->departure_at->format('d M Y H:i') }} UTC</p>
<div class="summary-line"><span>Base fare</span><strong>Rp {{ number_format($booking->base_fare ?: $booking->total_price,0,',','.') }}</strong></div>
<div class="summary-line"><span>Discount {{ $booking->promo_code ? '('.$booking->promo_code.')' : '' }}</span><strong>− Rp {{ number_format($booking->discount_amount,0,',','.') }}</strong></div>
<div class="summary-line"><span>Tax simulation</span><strong>Rp {{ number_format($booking->tax_amount,0,',','.') }}</strong></div>
<div class="divider"></div><div class="summary-line"><strong>Grand total</strong><strong>Rp {{ number_format($booking->total_price,0,',','.') }}</strong></div>
<p class="muted">Status: {{ strtoupper($booking->status) }}. {{ $booking->payments->where('status','succeeded')->count() ? 'Payment recorded.' : 'No successful payment recorded.' }}</p>
<p class="muted">This is a software-generated demonstration invoice and is NOT an airline-issued ticket or official tax invoice. Operators must configure and review legal/tax compliance before real commerce.</p>
<a class="btn btn-primary" href="{{ route('bookings.invoice.pdf',$booking) }}">Download PDF</a></div></section>
@endsection
