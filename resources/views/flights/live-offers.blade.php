@extends('layouts.app')
@section('title','Live airline offer search')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">OPTIONAL INTEGRATION · DUFFEL</span><h1>Airline offer explorer</h1><p>Provider-powered read-only flight search. Search results are not bookings or confirmed fares. Prices, rules, and availability must be revalidated with the provider.</p></div></section>
<section class="section container">
@if(!$configured)<div class="card content-article"><h2>Provider not configured</h2><p>This environment uses synthetic flights by default. An operator must enable Duffel and supply credentials server-side to search external flight inventory.</p><a class="btn btn-primary" href="{{ route('flights.index') }}">Explore offline demo</a></div>
@else
<form method="POST" action="{{ route('flights.live.search') }}" class="filter-panel">@csrf
<label>Origin IATA<input required name="origin" maxlength="3" placeholder="CGK" value="{{ old('origin') }}"></label>
<label>Destination IATA<input required name="destination" maxlength="3" placeholder="DPS" value="{{ old('destination') }}"></label>
<label>Departure<input required name="date" type="date" min="{{ now()->toDateString() }}"></label>
<label>Trip type<select name="trip_type"><option value="one_way">One way</option><option value="round_trip">Return</option><option value="multi_city">Multi city</option></select></label>
<label>Return date<input name="return_date" type="date"></label>
<label>Second city IATA<input name="via" maxlength="3" placeholder="SIN"></label>
<label>Second segment date<input name="via_date" type="date"></label>
<label>Passengers<input name="passengers" type="number" min="1" max="6" value="1"></label>
<label>Cabin<select name="cabin_class"><option value="economy">Economy</option><option value="premium_economy">Premium economy</option><option value="business">Business</option><option value="first">First</option></select></label>
<button class="btn btn-primary" type="submit">Search airline offers</button>
</form>
@if($searched)<h2>{{ count($offers) }} offers returned</h2><div class="flight-grid">
@forelse($offers as $offer)<article class="card content-article">
<span class="eyebrow">AIRLINE OFFER · READ-ONLY</span>
<h3>{{ data_get($offer,'slices.0.segments.0.operating_carrier.name','Airline not specified') }}</h3>
<p>{{ data_get($offer,'slices.0.origin.name','Origin') }} → {{ data_get($offer,'slices.0.destination.name','Destination') }}</p>
<strong>{{ data_get($offer,'total_currency','') }} {{ data_get($offer,'total_amount','') }}</strong>
<p class="muted">Offer expires: {{ data_get($offer,'expires_at','check provider') }}</p>
<p class="muted">Baggage, fare rules, and order fulfillment depend on the airline offer; no purchase is available from this page.</p>
</article>@empty<div class="empty-state wide">No offers returned. Check IATA codes and dates.</div>@endforelse
</div>@endif
@endif
</section>
@endsection
