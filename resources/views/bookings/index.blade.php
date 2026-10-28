@extends('layouts.app')
@section('title', 'Reservasi saya')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">MY TRIPS</span><h1>Reservasi saya</h1><p>Semua rencana perjalananmu tersimpan di sini, {{ auth()->user()->name }}.</p></div></section>
<section class="section container"><div class="section-heading"><div><h2>Riwayat reservasi</h2><p>Reservasi simulasi, tanpa penerbitan tiket resmi.</p></div><a href="{{ route('flights.index') }}" class="btn btn-primary btn-small">Cari penerbangan +</a></div>
    <div class="booking-list">@forelse($bookings as $booking)
        <a class="booking-row" href="{{ route('bookings.show', $booking) }}"><span class="airline-icon">✈</span><div class="booking-main"><strong>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}</strong><small>{{ $booking->reference }} · {{ $booking->flight->departure_at->format('d M Y, H:i') }}</small></div><span class="status {{ $booking->status === 'reserved' ? 'status-active' : 'status-cancelled' }}">{{ $booking->status === 'reserved' ? 'Reserved (demo)' : 'Cancelled' }}</span><strong class="booking-price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong><span>→</span></a>
    @empty<div class="empty-state"><h3>Belum ada reservasi.</h3><p>Mulai dengan memilih penerbangan demo yang kamu inginkan.</p><a href="{{ route('flights.index') }}" class="btn btn-primary">Jelajahi penerbangan</a></div>@endforelse</div>
    @include('components.pagination', ['paginator' => $bookings])
</section>
@endsection
