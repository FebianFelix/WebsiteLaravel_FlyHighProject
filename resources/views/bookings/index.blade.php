@extends('layouts.app')
@section('title', 'Reservasi saya')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">MY TRIPS</span><h1>Reservasi saya</h1><p>Kelola seluruh perjalanan demo dari satu tempat, {{ auth()->user()->name }}.</p></div></section>
<section class="section container"><div class="notice-panel card"><strong>Itinerary planner</strong><p>Book two or more connected demo flights as one reservation.</p><a class="btn btn-outline btn-small" href="{{ route('itineraries.create') }}">Create itinerary →</a></div><div class="section-heading"><div><h2>Riwayat reservasi</h2><p>Reservasi simulasi, bukan tiket maskapai resmi.</p></div><a href="{{ route('flights.index') }}" class="btn btn-primary btn-small">Cari penerbangan +</a></div>
<div class="booking-list">@forelse($bookings as $booking)
<a class="booking-row" href="{{ route('bookings.show', $booking) }}"><span class="airline-icon">✈</span><div class="booking-main"><strong>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}</strong><small>{{ $booking->reference }} · {{ $booking->flight->departure_at->format('d M Y, H:i') }} UTC</small></div><span class="status {{ $booking->status === 'confirmed' ? 'status-active' : ($booking->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">{{ ucfirst($booking->status) }}</span><strong class="booking-price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong><span>→</span></a>
@empty<div class="empty-state"><h3>Belum ada reservasi.</h3><p>Pilih jadwal penerbangan demo untuk memulai.</p><a href="{{ route('flights.index') }}" class="btn btn-primary">Jelajahi penerbangan</a></div>@endforelse</div>
@include('components.pagination', ['paginator' => $bookings])
</section>
@endsection
