<?php

namespace Database\Seeders;

use App\Models\AuditEvent;
use App\Models\Booking;
use App\Models\ContentPage;
use App\Models\Flight;
use App\Models\PromoCode;
use App\Models\RefundRequest;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class PresentationSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('demo') || config('database.default') !== 'sqlite') {
            throw new RuntimeException('Presentation fixtures are permitted only in the SQLite demo.');
        }
        $this->call(DemoSeeder::class);
        if (Booking::query()->exists()) {
            return; // preserve presentation interactions on container restart
        }

        $routes = [
            ['DEMO201', 'Jakarta', 'Bali', 2, 7, 1250000, 72, 'economy'],
            ['DEMO202', 'Bali', 'Jakarta', 3, 17, 1375000, 72, 'economy'],
            ['DEMO203', 'Jakarta', 'Singapore', 4, 9, 1900000, 54, 'business'],
            ['DEMO204', 'Singapore', 'Tokyo', 5, 13, 3650000, 60, 'economy'],
            ['DEMO205', 'Tokyo', 'Jakarta', 7, 16, 4600000, 48, 'premium_economy'],
            ['DEMO206', 'Jakarta', 'Yogyakarta', 6, 8, 850000, 96, 'economy'],
            ['DEMO207', 'Yogyakarta', 'Jakarta', 8, 16, 880000, 96, 'economy'],
            ['DEMO208', 'Bali', 'Singapore', 9, 11, 1750000, 60, 'economy'],
            ['DEMO209', 'Singapore', 'Bali', 10, 18, 1820000, 60, 'economy'],
            ['DEMO210', 'Jakarta', 'Bali', 14, 10, 1520000, 72, 'business'],
        ];
        foreach ($routes as [$number, $origin, $destination, $days, $hour, $price, $seats, $cabin]) {
            $depart = now()->startOfDay()->addDays($days)->addHours($hour);
            Flight::updateOrCreate(['flight_number' => $number], [
                'airline' => 'FlyHigh Demo Airways',
                'origin' => $origin,
                'destination' => $destination,
                'origin_code' => self::airport($origin),
                'destination_code' => self::airport($destination),
                'departure_at' => $depart,
                'arrival_at' => $depart->copy()->addHours($destination === 'Tokyo' ? 7 : 2),
                'price' => $price,
                'seats_total' => $seats,
                'cabin_class' => $cabin,
                'carry_on_kg' => 7,
                'checked_baggage_kg' => $cabin === 'business' ? 30 : 20,
                'aircraft_type' => 'Demo Airbus A320',
                'fare_rules' => 'Fictional demonstration fare. This is not a real airline service.',
                'is_active' => true,
            ]);
        }
        $traveler = User::where('email', 'traveler@flyhigh.test')->firstOrFail();
        $bookings = app(BookingService::class);
        $confirmed = null;
        foreach (['DEMO201','DEMO203','DEMO206','DEMO208','DEMO210'] as $i => $flightNo) {
            $booking = $bookings->reserve(
                $traveler,
                Flight::where('flight_number', $flightNo)->firstOrFail()->id,
                [$i === 0 ? 'Demo Traveler' : 'Presentation Guest '.($i + 1)],
                (string) Str::uuid(),
                [],
                $i === 0 ? 'DEMO10' : null
            );
            if ($i < 4) {
                $bookings->settleDemo($booking);
                if ($i === 0) $confirmed = $booking;
            }
            // Historical activity for populated Argus/Athena charts.
            $booking->forceFill([
                'created_at' => now()->subDays(5 - $i),
                'updated_at' => now()->subDays(5 - $i),
            ])->save();
        }
        if ($confirmed) {
            RefundRequest::firstOrCreate(['booking_id' => $confirmed->id], [
                'user_id' => $traveler->id,
                'reason' => 'Presentation example: request for refund review, no real funds.',
                'status' => 'requested',
                'amount' => $confirmed->total_price,
            ]);
        }
        PromoCode::firstOrCreate(['code' => 'FLYHIGH20'], [
            'type' => 'percentage', 'value' => 20, 'max_uses' => 100, 'is_active' => true,
        ]);
        ContentPage::firstOrCreate(['slug' => 'presentation-guide'], [
            'title' => 'Presentation guide',
            'body' => "Explore sample flights as a guest, then sign in to reserve.\n\nTraveler: history, favorites, seat selection and simulation payments.\nHephaestus: website settings, flight catalog, CMS and promo codes.\nArgus: booking monitoring, chart activity, refund requests and audit trail.\nAthena: both panels plus user role assignment and refund decisions.\n\nAll transactions and travel information are fictional.",
            'is_published' => true,
        ]);
        AuditEvent::firstOrCreate(['action' => 'presentation.initialized', 'subject' => 'demo'], [
            'actor_user_id' => User::where('email','athena@flyhigh.test')->value('id'),
            'details' => ['dataset' => 'synthetic', 'version' => '2.0'],
        ]);
    }

    private static function airport(string $city): string
    {
        return match($city) {
            'Jakarta' => 'CGK',
            'Bali' => 'DPS',
            'Singapore' => 'SIN',
            'Tokyo' => 'NRT',
            'Yogyakarta' => 'YIA',
            default => 'XXX',
        };
    }
}
