<!doctype html><html><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;color:#132f47;padding:35px;font-size:12px}h1{font-size:30px}table{width:100%;border-collapse:collapse}td{padding:12px;border-bottom:1px solid #eee}.total{font-size:20px;font-weight:bold}small{color:#666}.tag{background:#f1f4f6;padding:6px}</style></head><body>
<h1>FlyHigh · Invoice</h1><p>Reference: {{ $booking->reference }} · {{ $booking->created_at->format('Y-m-d') }}</p>
<p>Customer: {{ $booking->user->name }} · {{ $booking->user->email }}</p>
<p>Flight: {{ $booking->flight->flight_number }} · {{ $booking->flight->origin }} - {{ $booking->flight->destination }}</p>
<table><tr><td>Base fare</td><td>Rp {{ number_format($booking->base_fare ?: $booking->total_price) }}</td></tr>
<tr><td>Discount</td><td>- Rp {{ number_format($booking->discount_amount) }}</td></tr>
<tr><td>Tax simulation</td><td>Rp {{ number_format($booking->tax_amount) }}</td></tr>
<tr><td class="total">Total</td><td class="total">Rp {{ number_format($booking->total_price) }}</td></tr></table>
<p class="tag">Status: {{ strtoupper($booking->status) }}</p>
<p><small>DEMONSTRATION DOCUMENT · Not an airline-issued flight ticket or official tax invoice.</small></p><p><small>{{ config('flyhigh.copyright') }} · {{ config('flyhigh.origin') }}</small></p>
</body></html>
