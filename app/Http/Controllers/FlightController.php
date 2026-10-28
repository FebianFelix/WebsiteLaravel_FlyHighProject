<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
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
        ]);

        $query = Flight::query()->where('is_active', true)
            ->where('departure_at', '>', now());

        if (! empty($filters['origin'])) {
            $query->where('origin', 'like', '%'.$filters['origin'].'%');
        }
        if (! empty($filters['destination'])) {
            $query->where('destination', 'like', '%'.$filters['destination'].'%');
        }
        if (! empty($filters['date'])) {
            $query->whereDate('departure_at', $filters['date']);
        }

        $flights = $query->orderBy('departure_at')->paginate(9)->withQueryString();

        return view('flights.index', compact('flights', 'filters'));
    }

    public function show(Flight $flight): View
    {
        abort_unless($flight->is_active && $flight->departure_at->isFuture(), 404);

        return view('flights.show', compact('flight'));
    }
}
