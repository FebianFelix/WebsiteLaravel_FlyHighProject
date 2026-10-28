@extends('layouts.app')
@section('title', 'Kelola reservasi')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.dashboard') }}">← Admin dashboard</a><span class="eyebrow">RESERVATION MANAGEMENT</span><h1>Semua reservasi</h1><p>Monitoring reservasi contoh pengguna FlyHigh.</p></div></section>
<section class="section container"><form class="filter-panel" method="GET">
<label>Status<select name="status"><option value="">Semua status</option>@foreach(['pending','confirmed','cancelled','expired'] as $status)<option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach</select></label>
<label>Kode reservasi<input name="search" value="{{ request('search') }}" maxlength="100" placeholder="FH-..."></label>
<button class="btn btn-primary" type="submit">Filter</button><a class="btn btn-outline" href="{{ route('admin.bookings.export') }}">Export CSV</a></form>
<div class="card table-wrap"><table><thead><tr><th>Booking</th><th>Pelanggan</th><th>Penerbangan</th><th>Penumpang</th><th>Total demo</th><th>Status</th></tr></thead><tbody>
@forelse($bookings as $booking)<tr><td><a class="text-link" href="{{ route('bookings.show', $booking) }}">{{ $booking->reference }}</a><small class="block">{{ $booking->created_at->format('d M Y') }}</small></td><td>{{ $booking->user->name }}<small class="block">{{ $booking->user->email }}</small></td><td>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}<small class="block">{{ $booking->flight->flight_number }}</small></td><td>{{ $booking->seats }}</td><td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td><td><span class="status {{ $booking->status === 'confirmed' ? 'status-active' : ($booking->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">{{ ucfirst($booking->status) }}</span></td></tr>
@empty<tr><td colspan="6">Belum ada reservasi.</td></tr>@endforelse</tbody></table></div>
@include('components.pagination', ['paginator' => $bookings])</section>
@endsection
