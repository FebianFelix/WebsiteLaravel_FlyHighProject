<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function index(): View
    {
        $flights = Flight::orderBy('departure_at', 'desc')->paginate(15);

        return view('admin.flights.index', compact('flights'));
    }

    public function create(): View
    {
        return view('admin.flights.form', ['flight' => new Flight()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Flight::create($data);

        return redirect()->route('admin.flights.index')->with('success', 'Penerbangan ditambahkan.');
    }

    public function edit(Flight $flight): View
    {
        return view('admin.flights.form', compact('flight'));
    }

    public function update(Request $request, Flight $flight): RedirectResponse
    {
        $data = $this->validated($request, $flight);

        if ($data['seats_total'] < $flight->reservedSeats()) {
            throw ValidationException::withMessages([
                'seats_total' => 'Kapasitas tidak boleh lebih kecil dari kursi yang sudah dipesan.',
            ]);
        }

        $flight->update($data);

        return redirect()->route('admin.flights.index')->with('success', 'Penerbangan diperbarui.');
    }

    public function destroy(Flight $flight): RedirectResponse
    {
        // Preserve historical reservations; never delete a flight with bookings.
        $flight->update(['is_active' => false]);

        return back()->with('success', 'Penerbangan dinonaktifkan.');
    }

    private function validated(Request $request, ?Flight $flight = null): array
    {
        $data = $request->validate([
            'flight_number' => ['required', 'string', 'max:20', Rule::unique('flights', 'flight_number')->ignore($flight?->id)],
            'airline' => ['required', 'string', 'max:100'],
            'origin' => ['required', 'string', 'max:100', 'different:destination'],
            'destination' => ['required', 'string', 'max:100'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after:departure_at'],
            'price' => ['required', 'integer', 'min:1000', 'max:1000000000'],
            'seats_total' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
