@extends('layouts.app')
@section('title', 'Business dashboard')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">FLYHIGH · OPERATIONS</span><h1>Business dashboard</h1><p>Analitik operasional reservasi simulasi, bukan transaksi penjualan aktual.</p></div></section>
<section class="section container">
<div class="stats-grid"><div class="stat-card"><span>Penerbangan</span><strong>{{ $summary['flights'] }}</strong></div><div class="stat-card"><span>Seluruh reservasi</span><strong>{{ $summary['bookings'] }}</strong></div><div class="stat-card"><span>Dikonfirmasi</span><strong>{{ $summary['confirmed'] }}</strong></div><div class="stat-card"><span>Menunggu</span><strong>{{ $summary['pending'] }}</strong></div><div class="stat-card"><span>Pembatalan</span><strong>{{ $summary['cancelled'] }}</strong></div><div class="stat-card"><span>Pengguna</span><strong>{{ $summary['members'] }}</strong></div></div>
<div class="card"><span class="eyebrow">DEMO GROSS BOOKING VALUE</span><h2>Rp {{ number_format($summary['gross_booking_value'], 0, ',', '.') }}</h2><p class="muted">Total nilai booking terkonfirmasi dalam simulator, bukan revenue atau uang sungguhan.</p>
<div class="divider"></div><h3>Reservasi 7 hari terakhir</h3>
<div class="analytics-bars">@foreach($daily as $day)<div class="analytics-day"><strong>{{ $day['count'] }}</strong><div class="analytics-column" style="height:{{ max(8, ($day['count'] / max(1, $daily->max('count'))) * 85) }}px" aria-label="{{ $day['count'] }} booking"></div><small>{{ $day['label'] }}</small></div>@endforeach</div>
</div>
<div class="section-heading"><div><h2>Reservasi terbaru</h2><p>Aktivitas terakhir dalam sistem demo.</p></div><div class="actions"><a class="btn btn-ghost btn-small" href="{{ route('admin.portal') }}">Control center</a>@if(auth()->user()->canManageConfig())<a class="btn btn-outline btn-small" href="{{ route('admin.flights.index') }}">Kelola penerbangan</a>@endif<a class="btn btn-primary btn-small" href="{{ route('admin.bookings.index') }}">Semua reservasi</a><a class="btn btn-outline btn-small" href="{{ route('admin.refunds.index') }}">Refund requests</a></div></div>
<div class="card table-wrap"><table><thead><tr><th>Kode</th><th>Member</th><th>Rute</th><th>Status</th><th>Nilai demo</th></tr></thead><tbody>
@forelse($recentBookings as $booking)<tr><td><a class="text-link" href="{{ route('bookings.show', $booking) }}">{{ $booking->reference }}</a></td><td>{{ $booking->user->name }}</td><td>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}</td><td>{{ ucfirst($booking->status) }}</td><td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
@empty<tr><td colspan="5">Belum ada reservasi.</td></tr>@endforelse
</tbody></table></div></section>
@endsection
