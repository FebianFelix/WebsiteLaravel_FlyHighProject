<?php

namespace Tests\Feature;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnvironmentPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_mode_requires_explicit_opt_in_for_synthetic_bookings(): void
    {
        config(['flyhigh.booking_mode' => 'catalog_only']);
        $flight = Flight::create([
            'flight_number'=>'SAF001', 'airline'=>'Sample Airline',
            'origin'=>'Jakarta', 'destination'=>'Bali',
            'departure_at'=>now()->addDays(5), 'arrival_at'=>now()->addDays(5)->addHours(2),
            'seats_total'=>12, 'price'=>1500000, 'is_active'=>true,
        ]);
        $this->get(route('flights.show', $flight))->assertOk();
        $this->actingAs(User::factory()->create())->get(route('bookings.create',$flight))->assertStatus(503);
        $this->post(route('bookings.store'),[
            'flight_id'=>$flight->id,'passengers'=>['Traveler']
        ])->assertStatus(503);
        $this->get(route('itineraries.create'))->assertStatus(503);
    }

    public function test_environment_doctor_detects_missing_application_key(): void
    {
        config(['app.key'=>'']);
        $this->artisan('flyhigh:doctor')->assertExitCode(1);
    }

    public function test_doctor_rejects_unsafe_production_settings_when_running_in_production(): void
    {
        app()->detectEnvironment(fn () => 'production');
        config([
            'app.key'=>'base64:'.base64_encode(random_bytes(32)),
            'app.debug'=>true,
            'app.url'=>'http://example.test',
            'session.secure'=>false,
            'flyhigh.demo_payment_enabled'=>true,
        ]);
        $this->artisan('flyhigh:doctor')->assertExitCode(1);
    }
}
