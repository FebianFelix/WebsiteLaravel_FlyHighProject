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
            'max_price' => ['nullable', 'integer', 'min:1000', 'max:1000000000'],
            'sort' => ['nullable', Rule::in(['departure', 'price_asc', 'price_desc'])],
        ]);

        $query = Flight::query()->where('is_active', true)->where('departure_at', '>', now());
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
        return view('flights.index', compact('flights', 'filters', 'airlines'));
    }

    public function show(Flight $flight): View
    {
        abort_unless($flight->is_active && $flight->departure_at->isFuture(), 404);
        return view('flights.show', compact('flight'));
    }
}
