@extends('layouts.app')
@section('title','Refund operations')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.dashboard') }}">← Dashboard</a><span class="eyebrow">ARGUS · MONITORING</span><h1>Refund requests</h1><p>Review user requests. Only Athena can approve or reject actual financial actions.</p></div></section>
<section class="section container"><div class="card table-wrap"><table><thead><tr><th>Booking</th><th>Traveler</th><th>Reason</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead><tbody>
@forelse($refunds as $refund)<tr><td>{{ $refund->booking->reference }}</td><td>{{ $refund->booking->user->email }}</td><td>{{ $refund->reason }}</td><td>Rp {{ number_format($refund->amount) }}</td><td>{{ strtoupper($refund->status) }}</td><td>
@if(in_array(auth()->user()->role,['athena','admin'],true) && $refund->status==='requested')
<form method="POST" action="{{ route('admin.refunds.approve',$refund) }}" onsubmit="return confirm('Confirm provider refund for this booking?')">@csrf<button class="btn btn-primary btn-small">Approve</button></form>
<form method="POST" action="{{ route('admin.refunds.reject',$refund) }}">@csrf<button class="btn btn-outline btn-small">Reject</button></form>
@else—@endif
</td></tr>@empty<tr><td colspan="6">No refund requests.</td></tr>@endforelse
</tbody></table></div>{{ $refunds->links() }}</section>
@endsection
