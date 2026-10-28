@extends('layouts.app')
@section('title', 'Kelola reservasi')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.dashboard') }}">← Admin dashboard</a><span class="eyebrow">RESERVATION MONITORING</span><h1>Semua reservasi</h1><p>Pantau reservasi contoh dari seluruh pengguna.</p></div></section>
<section class="section container"><div class="card table-wrap"><table><thead><tr><th>Booking</th><th>Pelanggan</th><th>Penerbangan</th><th>Penumpang</th><th>Total</th><th>Status</th></tr></thead><tbody>
@forelse($bookings as $booking)<tr><td><a class="text-link" href="{{ route('bookings.show', $booking) }}">{{ $booking->reference }}</a><small class="block">{{ $booking->created_at->format('d M Y') }}</small></td><td>{{ $booking->user->name }}<small class="block">{{ $booking->user->email }}</small></td><td>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}<small class="block">{{ $booking->flight->flight_number }}</small></td><td>{{ $booking->seats }}</td><td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td><td><span class="status {{ $booking->status === 'reserved' ? 'status-active' : 'status-cancelled' }}">{{ ucfirst($booking->status) }}</span></td></tr>
@empty<tr><td colspan="6">Belum ada reservasi.</td></tr>@endforelse
</tbody></table></div>@include('components.pagination', ['paginator' => $bookings])</section>
@endsection
