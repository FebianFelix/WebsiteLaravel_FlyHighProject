<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function home(): View
    {
        $flights = Flight::query()->where('is_active', true)
            ->where('departure_at', '>', now())
            ->orderBy('departure_at')->limit(4)->get();
        return view('home', compact('flights'));
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'origin' => ['nullable', 'string', 'max:100'],
            'destination' => ['nullable', 'string', 'max:100'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'airline' => ['nullable', 'string', 'max:100'],
            'trip_type' => ['nullable', Rule::in(['one_way', 'round_trip', 'multi_city'])],
            'return_date' => ['nullable', 'date_format:Y-m-d'],
            'via' => ['nullable', 'string', 'max:100'],
            'via_date' => ['nullable', 'date_format:Y-m-d'],
            'max_price' => ['nullable', 'integer', 'min:1000', 'max:1000000000'],
            'sort' => ['nullable', Rule::in(['departure', 'price_asc', 'price_desc'])],
        ]);

        $query = Flight::query()->where('is_active', true)->where('departure_at', '>', now());
        if (($filters['trip_type'] ?? '') === 'multi_city' && ! empty($filters['via'])) {
            $query->where('destination', $filters['via']);
        }
        foreach (['origin', 'destination', 'airline'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, 'like', '%'.addcslashes($filters[$field], '%_\\').'%');
            }
        }
        if (! empty($filters['date'])) { $query->whereDate('departure_at', $filters['date']); }
        if (! empty($filters['max_price'])) { $query->where('price', '<=', $filters['max_price']); }

        match ($filters['sort'] ?? 'departure') {
            'price_asc' => $query->orderBy('price')->orderBy('departure_at'),
            'price_desc' => $query->orderByDesc('price')->orderBy('departure_at'),
            default => $query->orderBy('departure_at'),
        };
        $flights = $query->paginate(9)->withQueryString();
        $airlines = Flight::where('is_active', true)->distinct()->orderBy('airline')->pluck('airline');
        $secondLegFlights = null;
        if (($filters['trip_type'] ?? '') === 'round_trip' && ! empty($filters['origin']) && ! empty($filters['destination']) && ! empty($filters['return_date'])) {
            $secondLegFlights = Flight::where('is_active', true)->where('departure_at', '>', now())
                ->where('origin', $filters['destination'])->where('destination', $filters['origin'])
                ->whereDate('departure_at', $filters['return_date'])->orderBy('departure_at')->limit(12)->get();
        }
        if (($filters['trip_type'] ?? '') === 'multi_city' && ! empty($filters['via']) && ! empty($filters['destination']) && ! empty($filters['via_date'])) {
            $secondLegFlights = Flight::where('is_active', true)->where('departure_at', '>', now())
                ->where('origin', $filters['via'])->where('destination', $filters['destination'])
                ->whereDate('departure_at', $filters['via_date'])->orderBy('departure_at')->limit(12)->get();
        }
        return view('flights.index', compact('flights', 'filters', 'airlines', 'secondLegFlights'));
    }

    public function show(Flight $flight): View
    {
        abort_unless($flight->is_active && $flight->departure_at->isFuture(), 404);
        return view('flights.show', compact('flight'));
    }
}
