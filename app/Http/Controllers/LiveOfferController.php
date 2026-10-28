<?php

namespace App\Http\Controllers;

use App\Services\DuffelOffers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LiveOfferController extends Controller
{
    public function index(DuffelOffers $duffel): View
    {
        return view('flights.live-offers', ['offers' => [], 'configured' => $duffel->enabled(), 'searched' => false]);
    }

    public function search(Request $request, DuffelOffers $duffel): View
    {
        abort_unless($duffel->enabled(), 503, 'Duffel is not configured for this environment.');
        $data = $request->validate([
            'origin' => ['required', 'regex:/^[A-Za-z]{3}$/'],
            'destination' => ['required', 'regex:/^[A-Za-z]{3}$/', 'different:origin'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'trip_type' => ['required', Rule::in(['one_way','round_trip','multi_city'])],
            'return_date' => ['required_if:trip_type,round_trip', 'nullable', 'date_format:Y-m-d', 'after_or_equal:date'],
            'via' => ['required_if:trip_type,multi_city', 'nullable', 'regex:/^[A-Za-z]{3}$/'],
            'via_date' => ['required_if:trip_type,multi_city', 'nullable', 'date_format:Y-m-d', 'after_or_equal:date'],
            'passengers' => ['required', 'integer', 'min:1', 'max:6'],
            'cabin_class' => ['required', Rule::in(['economy','premium_economy','business','first'])],
        ]);
        if (($data['trip_type'] === 'multi_city') && strtoupper($data['via']) === strtoupper($data['origin'])) {
            return back()->withErrors(['via' => 'Second city must differ from the departure city.'])->withInput();
        }
        return view('flights.live-offers', [
            'offers' => $duffel->search($data), 'configured' => true, 'searched' => true,
        ]);
    }
}
