<!doctype html><html><head><meta charset="UTF-8"><style>
@page{margin:40px 44px}body{font-family:DejaVu Sans,sans-serif;color:#10263f;font-size:12px}
.header{border-bottom:3px solid #357b79;padding-bottom:17px;margin-bottom:25px}
h1{font-size:27px;margin:7px 0}h2{font-size:19px}small{color:#708090} .tag{font-size:10px;color:#357b79;font-weight:bold}
table{width:100%;border-collapse:collapse;margin-top:14px}td{padding:10px 4px;border-bottom:1px solid #e4e8ec}
.box{border:1px solid #dde3e9;padding:18px;border-radius:8px;margin:20px 0}.warn{background:#f8eded;color:#9b4343;padding:14px;margin-top:25px;font-size:11px}
.footer{border-top:1px solid #ddd;margin-top:40px;padding-top:12px;color:#87909c;font-size:10px}
</style></head><body>
<div class="header"><span class="tag">FLYHIGH · PORTFOLIO EDITION · DEMO</span><h1>Flight Itinerary</h1><small>Confirmation reference: {{ $booking->reference }}</small></div>
<div class="box"><h2>{{ $booking->flight->origin }} → {{ $booking->flight->destination }}</h2>
<p>{{ $booking->flight->airline }} / {{ $booking->flight->flight_number }}</p>
<table><tr><td>Departure (UTC)</td><td><strong>{{ $booking->flight->departure_at->format('d M Y, H:i') }}</strong></td></tr>
<tr><td>Arrival (UTC)</td><td><strong>{{ $booking->flight->arrival_at->format('d M Y, H:i') }}</strong></td></tr>
<tr><td>Status</td><td><strong>Confirmed (demonstration)</strong></td></tr>
<tr><td>Passengers</td><td>{{ $booking->seats }}</td></tr>
<tr><td>Illustrative price</td><td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr></table></div>
<h2>Passengers</h2><ol>@foreach($booking->passengers as $passenger)<li>{{ $passenger->full_name }}</li>@endforeach</ol>
<h2>Verification link</h2><p style="overflow-wrap:anywhere">{{ $verificationUrl }}</p>
<div class="warn"><strong>NOT VALID FOR TRAVEL:</strong> No real reservation, boarding pass, airline inventory, or payment is provided. This document demonstrates Laravel application functionality.</div>
<div class="footer">FlyHigh Portfolio | Original project: FebianFelix | https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject</div>
</body></html>
