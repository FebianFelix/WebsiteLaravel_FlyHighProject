<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            ['FH101', 'FlyHigh Air', 'Jakarta', 'Bali', 2, '08:10', 2, 1250000, 90],
            ['FH102', 'FlyHigh Air', 'Jakarta', 'Singapore', 3, '11:20', 2, 1950000, 110],
            ['FH103', 'Nusantara Air', 'Surabaya', 'Jakarta', 4, '09:15', 2, 980000, 100],
            ['FH104', 'FlyHigh Air', 'Jakarta', 'Bangkok', 5, '07:00', 4, 2350000, 110],
            ['FH105', 'Archipelago Jet', 'Bali', 'Lombok', 6, '13:00', 1, 780000, 80],
            ['FH106', 'Nusantara Air', 'Medan', 'Jakarta', 7, '14:30', 3, 1490000, 120],
            ['FH107', 'FlyHigh Air', 'Jakarta', 'Yogyakarta', 8, '10:15', 1, 890000, 100],
            ['FH108', 'Archipelago Jet', 'Makassar', 'Bali', 9, '12:05', 2, 1350000, 100],
        ];

        foreach ($routes as [$number, $airline, $origin, $destination, $days, $departure, $hours, $price, $seats]) {
            $departureAt = now()->startOfDay()->addDays($days)->setTimeFromTimeString($departure);

            Flight::updateOrCreate(['flight_number' => $number], [
                'airline' => $airline,
                'origin' => $origin,
                'destination' => $destination,
                'departure_at' => $departureAt,
                'arrival_at' => $departureAt->copy()->addHours($hours),
                'price' => $price,
                'seats_total' => $seats,
                'is_active' => true,
            ]);
        }
    }
}
