<article class="flight-card">
    <div class="flight-head"><span class="airline-icon">✈</span><div><strong>{{ $flight->airline }}</strong><small>{{ $flight->flight_number }}</small></div><span class="pill">Demo</span></div>
    <div class="flight-route">
        <div><strong>{{ $flight->departure_at->format('H:i') }}</strong><span>{{ $flight->origin }}</span></div>
        <div class="route-line"><span>↗</span><small>{{ $flight->departure_at->format('d M Y') }}</small></div>
        <div class="text-right"><strong>{{ $flight->arrival_at->format('H:i') }}</strong><span>{{ $flight->destination }}</span></div>
    </div>
    <div class="flight-bottom">
        <div><small>Mulai dari / penumpang</small><strong class="price">Rp {{ number_format($flight->price, 0, ',', '.') }}</strong></div>
        <a href="{{ route('flights.show', $flight) }}" class="btn btn-primary btn-small">Lihat detail →</a>
    </div>
</article>
