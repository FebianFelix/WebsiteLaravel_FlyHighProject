<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentEvent;
use App\Services\BookingService;
use App\Services\StripeGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StripeWebhookController extends Controller
{
    public function __construct(private readonly StripeGateway $stripe, private readonly BookingService $bookings) {}

    public function handle(Request $request): JsonResponse
    {
        $event = $this->stripe->verifiedEvent($request);
        if (PaymentEvent::where('provider', 'stripe')->where('event_id', $event['id'])->exists()) {
            return response()->json(['received' => true]);
        }
        $status = 'ignored';
        $description = null;
        if ($event['type'] === 'checkout.session.completed') {
            $object = data_get($event, 'data.object', []);
            if (($object['payment_status'] ?? null) === 'paid' && ($object['currency'] ?? null) === 'idr') {
                $booking = Booking::find($object['metadata']['booking_id'] ?? 0);
                if ($booking && is_string($object['id'] ?? null) && is_numeric($object['amount_total'] ?? null)) {
                    try {
                        $this->bookings->settleStripe(
                            $booking, $object['id'],
                            is_string($object['payment_intent'] ?? null) ? $object['payment_intent'] : null,
                            (int) $object['amount_total']
                        );
                        $status = 'accepted';
                    } catch (ValidationException $e) {
                        // Never confirm tickets for late, mismatched, or disputed payments.
                        $status = 'requires_review';
                        $description = 'Paid checkout not eligible for booking confirmation; reconcile in Stripe.';
                    }
                } else {
                    $status = 'requires_review';
                    $description = 'Unknown booking or malformed paid session.';
                }
            }
        }
        PaymentEvent::firstOrCreate(
            ['provider' => 'stripe', 'event_id' => $event['id']],
            ['status' => $status, 'description' => $description]
        );
        return response()->json(['received' => true]);
    }
}
