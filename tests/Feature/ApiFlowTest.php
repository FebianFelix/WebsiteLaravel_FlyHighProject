<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_public_flight_search_returns_data(): void
    {
        Flight::create([
            'flight_number' => 'API001', 'airline' => 'Demo',
            'origin' => 'Jakarta', 'destination' => 'Bali',
            'departure_at' => now()->addDays(2), 'arrival_at' => now()->addDays(2)->addHour(),
            'price' => 100000, 'seats_total' => 10, 'is_active' => true,
        ]);
        $this->getJson('/api/v1/flights?origin=Jakarta')->assertOk()
            ->assertJsonFragment(['flight_number' => 'API001']);
    }

    public function test_api_token_can_book_idempotently(): void
    {
        $flight = Flight::create([
            'flight_number' => 'API002', 'airline' => 'Demo',
            'origin' => 'Jakarta', 'destination' => 'Bali',
            'departure_at' => now()->addDays(2), 'arrival_at' => now()->addDays(2)->addHour(),
            'price' => 100000, 'seats_total' => 1, 'is_active' => true,
        ]);
        $user = User::factory()->create();
        $token = $user->createToken('my-key', ['flights:read', 'bookings:read', 'bookings:write'])->plainTextToken;
        $payload = [
            'flight_id' => $flight->id,
            'passengers' => ['Api Traveler'],
            'request_key' => (string) Str::uuid(),
        ];
        $this->withToken($token)->postJson('/api/v1/bookings', $payload)->assertCreated();
        $this->postJson('/api/v1/bookings', $payload)->assertCreated();
        $this->assertDatabaseCount('bookings', 1);
        $booking = Booking::firstOrFail();
        $this->getJson('/api/v1/bookings/'.$booking->id)->assertOk();

        $other = User::factory()->create();
        $otherToken = $other->createToken('other', ['bookings:read'])->plainTextToken;
        $this->app['auth']->forgetGuards();
        $this->withToken($otherToken)->getJson('/api/v1/bookings/'.$booking->id)->assertForbidden();
        $this->postJson('/api/v1/bookings', $payload)->assertForbidden();
    }
}
