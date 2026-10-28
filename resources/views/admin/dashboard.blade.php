@extends('layouts.app')
@section('title', 'Admin dashboard')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">FLYHIGH OPERATIONS</span><h1>Admin dashboard</h1><p>Kelola jadwal dan pantau aktivitas reservasi simulasi.</p></div></section>
<section class="section container">
    <div class="stats-grid"><div class="stat-card"><span>Total penerbangan</span><strong>{{ $flightCount }}</strong></div><div class="stat-card"><span>Total reservasi</span><strong>{{ $bookingCount }}</strong></div><div class="stat-card"><span>Akun member</span><strong>{{ $memberCount }}</strong></div></div>
    <div class="section-heading"><div><h2>Reservasi terbaru</h2><p>Aktivitas terakhir pada sistem demo.</p></div><div class="actions"><a class="btn btn-outline btn-small" href="{{ route('admin.flights.index') }}">Kelola penerbangan</a><a class="btn btn-primary btn-small" href="{{ route('admin.bookings.index') }}">Semua reservasi</a></div></div>
    <div class="card table-wrap"><table><thead><tr><th>Kode</th><th>Member</th><th>Rute</th><th>Status</th><th>Total</th></tr></thead><tbody>@forelse($recentBookings as $booking)
        <tr><td><a class="text-link" href="{{ route('bookings.show', $booking) }}">{{ $booking->reference }}</a></td><td>{{ $booking->user->name }}</td><td>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}</td><td>{{ ucfirst($booking->status) }}</td><td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
    @empty<tr><td colspan="5">Belum ada reservasi.</td></tr>@endforelse</tbody></table></div>
</section>
@endsection
