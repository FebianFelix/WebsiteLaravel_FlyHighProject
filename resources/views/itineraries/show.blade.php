@extends('layouts.app')
@section('title','Itinerary '.$itinerary->reference)
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">MULTI-LEG DEMO BOOKING</span><h1>{{ $itinerary->reference }}</h1><p>Status: {{ strtoupper($itinerary->status) }} · All segments are synthetic travel records.</p></div></section>
<section class="section container two-column"><div class="card content-article"><h2>Your segments</h2>
@foreach($itinerary->bookings as $booking)<div class="summary-line"><div><strong>Leg {{ $booking->leg_number }} · {{ $booking->flight->origin }} → {{ $booking->flight->destination }}</strong><small class="block">{{ $booking->flight->flight_number }} · {{ $booking->flight->departure_at->format('d M Y H:i') }} UTC · {{ $booking->seats }} seat(s) · {{ strtoupper($booking->status) }}</small></div><a class="text-link" href="{{ route('bookings.show',$booking) }}">Detail</a></div>@endforeach
</div><aside class="card summary-card"><span class="eyebrow">ALL-OR-NOTHING</span><h2>Summary</h2><strong class="price-large">Rp {{ number_format($itinerary->total_price,0,',','.') }}</strong>
@if($itinerary->status==='pending' && config('flyhigh.demo_payment_enabled'))<form method="POST" action="{{ route('itineraries.confirm',$itinerary) }}">@csrf<button class="btn btn-primary btn-block">Confirm ALL legs (demo only)</button></form>@endif
@if(in_array($itinerary->status,['pending','confirmed']))<form method="POST" action="{{ route('itineraries.cancel',$itinerary) }}">@csrf @method('PATCH')<button class="btn btn-outline btn-block">Cancel all legs</button></form>@endif
<p class="muted">No airline order is placed and no actual money is charged.</p></aside></section>
@endsection
