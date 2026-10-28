<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class DuffelOffers
{
    public function enabled(): bool
    {
        return config('flyhigh.flight_provider') === 'duffel'
            && filled(config('flyhigh.duffel_access_token'));
    }

    /** Offer search only: never creates airline orders or charges a customer. */
    public function search(array $data): array
    {
        abort_unless($this->enabled(), 503, 'External airline inventory is not configured.');
        $slices = [[
            'origin' => strtoupper($data['origin']), 'destination' => strtoupper($data['destination']),
            'departure_date' => $data['date'],
        ]];
        if (($data['trip_type'] ?? 'one_way') === 'round_trip') {
            $slices[] = [
                'origin' => strtoupper($data['destination']), 'destination' => strtoupper($data['origin']),
                'departure_date' => $data['return_date'],
            ];
        } elseif (($data['trip_type'] ?? 'one_way') === 'multi_city') {
            // The first segment ends at the connecting city, then flies onwards.
            $slices[0]['destination'] = strtoupper($data['via']);
            $slices[] = [
                'origin' => strtoupper($data['via']), 'destination' => strtoupper($data['destination']),
                'departure_date' => $data['via_date'],
            ];
        }
        $response = Http::withToken((string) config('flyhigh.duffel_access_token'))
            ->acceptJson()->withHeaders(['Duffel-Version' => 'v2'])->timeout(30)
            ->post('https://api.duffel.com/air/offer_requests?supplier_timeout=12000', [
                'data' => [
                    'slices' => $slices,
                    'passengers' => array_fill(0, (int) ($data['passengers'] ?? 1), ['type' => 'adult']),
                    'cabin_class' => $data['cabin_class'] ?? 'economy',
                ],
            ]);
        if (! $response->successful()) {
            throw ValidationException::withMessages(['provider' => 'Airline search provider unavailable. Check credentials and retry.']);
        }
        return array_slice((array) data_get($response->json(), 'data.offers', []), 0, 15);
    }
}
