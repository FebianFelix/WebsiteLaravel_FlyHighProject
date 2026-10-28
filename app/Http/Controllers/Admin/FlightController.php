<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function create(): View { return view('admin.flights.form', ['flight' => new Flight()]); }

    public function store(Request $request): RedirectResponse
    {
        Flight::create($this->validated($request));
        return redirect()->route('admin.flights.index')->with('success', 'Penerbangan ditambahkan.');
    }

    public function edit(Flight $flight): View { return view('admin.flights.form', compact('flight')); }

    public function update(Request $request, Flight $flight): RedirectResponse
    {
        $data = $this->validated($request, $flight);
        DB::transaction(function () use ($flight, $data) {
            $locked = Flight::query()->lockForUpdate()->findOrFail($flight->id);
            if ($data['seats_total'] < $locked->reservedSeats()) {
                throw ValidationException::withMessages(['seats_total' => 'Kapasitas lebih kecil dari kursi yang sudah dipesan.']);
            }
            // Disallow moving confirmed inventory to a different flight.
            if ($locked->bookings()->exists() && (
                $locked->departure_at->toDateTimeString() !== \Illuminate\Support\Carbon::parse($data['departure_at'])->toDateTimeString()
                || $locked->flight_number !== $data['flight_number']
                || $locked->origin !== $data['origin']
                || $locked->destination !== $data['destination']
            )) {
                throw ValidationException::withMessages(['flight_number' => 'Jadwal yang sudah memiliki reservasi tidak boleh dipindahkan. Buat penerbangan baru.']);
            }
            $locked->update($data);
        }, 3);
        return redirect()->route('admin.flights.index')->with('success', 'Penerbangan diperbarui.');
    }

    public function destroy(Flight $flight): RedirectResponse
    {
        Flight::query()->whereKey($flight->id)->update(['is_active' => false]);
        return back()->with('success', 'Penerbangan dinonaktifkan.');
    }

    private function validated(Request $request, ?Flight $flight = null): array
    {
        $data = $request->validate([
            'flight_number' => ['required', 'string', 'max:20',
                Rule::unique('flights', 'flight_number')
                    ->where(fn ($q) => $q->where('departure_at', str_replace('T', ' ', $request->input('departure_at')).(strlen((string) $request->input('departure_at')) === 16 ? ':00' : '')))
                    ->ignore($flight?->id)],
            'airline' => ['required', 'string', 'max:100'],
            'origin' => ['required', 'string', 'max:100', 'different:destination'],
            'destination' => ['required', 'string', 'max:100'],
            'origin_code' => ['nullable', 'alpha', 'size:3'],
            'destination_code' => ['nullable', 'alpha', 'size:3'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after:departure_at'],
            'price' => ['required', 'integer', 'min:1000', 'max:1000000000'],
            'seats_total' => ['required', 'integer', 'min:1', 'max:1000'],
            'cabin_class' => ['nullable', Rule::in(['economy','premium_economy','business','first'])],
            'carry_on_kg' => ['nullable','integer','min:0','max:50'],
            'checked_baggage_kg' => ['nullable','integer','min:0','max:100'],
            'aircraft_type' => ['nullable','string','max:80'],
            'fare_rules' => ['nullable','string','max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['cabin_class'] = $data['cabin_class'] ?? 'economy';
        $data['carry_on_kg'] = $data['carry_on_kg'] ?? 7;
        $data['checked_baggage_kg'] = $data['checked_baggage_kg'] ?? 20;
        foreach (['origin_code', 'destination_code'] as $key) {
            $data[$key] = isset($data[$key]) ? strtoupper($data[$key]) : null;
        }
        return $data;
    }
}
