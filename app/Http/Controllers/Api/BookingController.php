<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $service) {}

    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->tokenCan('bookings:read'), 403);
        return response()->json($request->user()->bookings()
            ->with('flight:id,flight_number,origin,destination,departure_at')
            ->latest()->paginate(20));
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        abort_unless($request->user()->tokenCan('bookings:read'), 403);
        abort_unless($request->user()->id === $booking->user_id, 403);
        return response()->json(['data' => $booking->load(['flight', 'passengers'])]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->tokenCan('bookings:write'), 403);
        $data = $request->validate([
            'flight_id' => ['required', 'integer', 'exists:flights,id'],
            'request_key' => ['nullable', 'uuid'],
            'passengers' => ['required', 'array', 'min:1', 'max:6'],
            'passengers.*' => ['required', 'string', 'max:120'],
            'promo_code' => ['nullable', 'string', 'max:40'],
            'seat_codes' => ['nullable', 'array', 'max:6'],
            'seat_codes.*' => ['required', 'string', 'regex:/^[1-9][0-9]{0,2}[A-F]$/'],
        ]);
        $key = $data['request_key'] ?? $request->header('Idempotency-Key') ?? (string) Str::uuid();
        if (! Str::isUuid($key)) {
            return response()->json(['message' => 'Idempotency-Key must be UUID'], 422);
        }
        $booking = $this->service->reserve($request->user(), (int) $data['flight_id'], $data['passengers'], $key, $data['seat_codes'] ?? [], $data['promo_code'] ?? null);
        return response()->json(['data' => $booking->load('passengers')], 201);
    }

    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        abort_unless($request->user()->tokenCan('bookings:write'), 403);
        abort_unless($request->user()->id === $booking->user_id, 403);
        abort_unless($booking->itinerary_id === null, 409, 'Cancel the entire itinerary instead.');
        return response()->json(['data' => $this->service->cancel($booking)]);
    }
}
