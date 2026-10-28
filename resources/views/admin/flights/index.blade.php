@extends('layouts.app')
@section('title', 'Kelola penerbangan')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.portal') }}">← Admin dashboard</a><span class="eyebrow">FLIGHT MANAGEMENT</span><h1>Kelola penerbangan</h1><p>Tambahkan, perbarui, atau nonaktifkan jadwal demo.</p></div></section>
<section class="section container"><div class="section-heading"><h2>Daftar penerbangan</h2><a class="btn btn-primary btn-small" href="{{ route('admin.flights.create') }}">+ Jadwal baru</a></div>
    <div class="card table-wrap"><table><thead><tr><th>Penerbangan</th><th>Rute</th><th>Berangkat</th><th>Harga</th><th>Kursi</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>@forelse($flights as $flight)<tr><td><strong>{{ $flight->flight_number }}</strong><small class="block">{{ $flight->airline }}</small></td><td>{{ $flight->origin }} → {{ $flight->destination }}</td><td>{{ $flight->departure_at->format('d M Y H:i') }}</td><td>Rp {{ number_format($flight->price, 0, ',', '.') }}</td><td>{{ $flight->availableSeats() }}/{{ $flight->seats_total }}</td><td><span class="status {{ $flight->is_active ? 'status-active' : 'status-cancelled' }}">{{ $flight->is_active ? 'Active' : 'Inactive' }}</span></td><td><div class="inline-actions"><a class="text-link" href="{{ route('admin.flights.edit', $flight) }}">Edit</a>@if($flight->is_active)<form method="POST" action="{{ route('admin.flights.destroy', $flight) }}" onsubmit="return confirm('Nonaktifkan jadwal ini?')">@csrf @method('DELETE')<button class="link-danger">Nonaktifkan</button></form>@endif</div></td></tr>
    @empty<tr><td colspan="7">Belum ada penerbangan.</td></tr>@endforelse</tbody></table></div>
    @include('components.pagination', ['paginator' => $flights])
</section>
@endsection
