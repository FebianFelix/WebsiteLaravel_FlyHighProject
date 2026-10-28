<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'origin' => ['nullable', 'string', 'max:100'],
            'destination' => ['nullable', 'string', 'max:100'],
        ]);
        $q = Flight::query()->where('is_active', true)->where('departure_at', '>', now());
        foreach ($data as $key => $value) {
            $q->where($key, 'like', '%'.addcslashes($value, '%_\\').'%');
        }
        return response()->json($q->orderBy('departure_at')->paginate(20));
    }

    public function show(Flight $flight): JsonResponse
    {
        abort_unless($flight->is_active && $flight->departure_at->isFuture(), 404);
        return response()->json([
            'data' => $flight->only([
                'id', 'flight_number', 'airline', 'origin', 'destination',
                'departure_at', 'arrival_at', 'price',
            ]),
            'available_seats' => $flight->availableSeats(),
        ]);
    }
}
