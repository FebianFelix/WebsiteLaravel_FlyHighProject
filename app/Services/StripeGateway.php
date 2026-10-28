<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class StripeGateway
{
    public function enabled(): bool
    {
        return config('flyhigh.payment_gateway') === 'stripe'
            && is_string(config('flyhigh.stripe_secret'))
            && str_starts_with((string) config('flyhigh.stripe_secret'), 'sk_test_')
            && filled(config('flyhigh.stripe_webhook_secret'))
            && ! config('flyhigh.demo_mode');
    }

    public function createCheckout(Booking $booking): array
    {
        abort_unless($this->enabled(), 503, 'Stripe payments are not configured.');
        $response = Http::asForm()
            ->withToken((string) config('flyhigh.stripe_secret'))
            ->timeout(15)->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'customer_email' => $booking->user->email,
                'client_reference_id' => $booking->reference,
                'metadata' => ['booking_id' => (string) $booking->id],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'idr',
                        'unit_amount' => $booking->total_price,
                        'product_data' => ['name' => 'FlyHigh booking '.$booking->reference],
                    ],
                    'quantity' => 1,
                ]],
                'expires_at' => now()->addMinutes(31)->timestamp,
                'success_url' => route('bookings.show', $booking).'?payment=processing',
                'cancel_url' => route('bookings.show', $booking).'?payment=cancelled',
            ]);
        if (! $response->successful() || ! is_string($response->json('id')) || ! is_string($response->json('url'))) {
            throw ValidationException::withMessages(['payment' => 'Payment provider unavailable. Please retry later.']);
        }
        return ['id' => $response->json('id'), 'url' => $response->json('url')];
    }

    public function verifiedEvent(Request $request): array
    {
        $secret = (string) config('flyhigh.stripe_webhook_secret');
        if (! $secret) abort(503, 'Webhook not configured');
        $header = $request->header('Stripe-Signature', '');
        $pairs = [];
        foreach (explode(',', $header) as $pair) {
            $parts = explode('=', trim($pair), 2);
            if (count($parts) === 2) $pairs[$parts[0]][] = $parts[1];
        }
        $timestamp = $pairs['t'][0] ?? null;
        if (! ctype_digit((string) $timestamp) || abs(time() - (int) $timestamp) > 300) {
            abort(400, 'Invalid signature timestamp.');
        }
        $expected = hash_hmac('sha256', $timestamp.'.'.$request->getContent(), $secret);
        if (! collect($pairs['v1'] ?? [])->contains(fn ($candidate) => hash_equals($expected, (string) $candidate))) {
            abort(400, 'Invalid signature.');
        }
        $event = json_decode($request->getContent(), true);
        if (! is_array($event) || ! is_string($event['id'] ?? null) || ! is_string($event['type'] ?? null)) {
            abort(400, 'Invalid event.');
        }
        return $event;
    }

    public function refund(string $paymentIntent, int $amount, int $requestId): array
    {
        abort_unless($this->enabled(), 503, 'Stripe is not configured.');
        $response = Http::asForm()->withToken((string) config('flyhigh.stripe_secret'))
            ->withHeaders(['Idempotency-Key' => 'flyhigh-refund-'.$requestId])
            ->timeout(15)->post('https://api.stripe.com/v1/refunds', [
                'payment_intent' => $paymentIntent,
                'amount' => $amount,
            ]);
        if (! $response->successful() || ! in_array($response->json('status'), ['succeeded', 'pending'], true)) {
            throw ValidationException::withMessages(['refund' => 'Stripe could not process the refund. Review it in the provider dashboard.']);
        }
        return ['id' => (string) $response->json('id'), 'status' => (string) $response->json('status')];
    }
}
