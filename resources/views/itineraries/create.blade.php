@extends('layouts.app')
@section('title','Build a multi-leg itinerary')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">TRIP PLANNER</span><h1>Build your itinerary</h1><p>Choose 2–4 flights in travel order. Connected cities and chronological departure times are verified server-side; inventory is reserved atomically.</p></div></section>
<section class="section container two-column"><div class="card form-card"><form class="stack-form" method="POST" action="{{ route('itineraries.store') }}">@csrf<input type="hidden" name="request_key" value="{{ old('request_key',$key) }}">
@for($leg=0;$leg<4;$leg++)
<label>Segment {{ $leg+1 }} {{ $leg<2?'(required)':'(optional)' }}<select name="flight_ids[]" {{ $leg<2?'required':'' }}><option value="">Choose a flight</option>
@foreach($flights as $flight)<option value="{{ $flight->id }}">{{ $flight->flight_number }} · {{ $flight->origin }} → {{ $flight->destination }} · {{ $flight->departure_at->format('d M H:i') }} · Rp {{ number_format($flight->price) }}</option>@endforeach</select></label>
@endfor
<p class="muted">Leave optional segment fields blank. Each leg is shown in your booking history individually as well.</p>
<label>Passenger name<input name="passengers[]" maxlength="120" required placeholder="Full name for the itinerary"></label>
<button type="submit" class="btn btn-primary btn-block">Reserve entire itinerary →</button></form></div>
<aside class="card summary-card"><span class="eyebrow">IMPORTANT</span><h2>Connected trips</h2><p>All legs must connect by destination and departure order. Currently one traveler is entered in this quick planner; for more travelers, use the single-leg booking form or extend the API payload.</p><p class="muted">This planner is for simulated inventory, not an airline-issued multi-city ticket. Payment simulator is all-or-nothing for the itinerary.</p></aside>
</section>
@endsection
