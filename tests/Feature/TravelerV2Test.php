<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class TravelerV2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    private function flight(): Flight
    {
        return Flight::create([
            'flight_number' => 'FHV20', 'airline' => 'FlyHigh',
            'origin' => 'Jakarta', 'destination' => 'Bali',
            'departure_at' => now()->addDays(7), 'arrival_at' => now()->addDays(7)->addHours(2),
            'price' => 300000, 'seats_total' => 3, 'is_active' => true,
        ]);
    }

    public function test_seats_are_reserved_automatically_and_released_on_cancel(): void
    {
        $flight = $this->flight();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('bookings.store'), [
            'flight_id' => $flight->id, 'passengers' => ['Traveler A'],
            'seat_codes' => ['1A'],
        ])->assertRedirect();
        $booking = Booking::firstOrFail();
        $this->assertDatabaseHas('booking_seats', ['flight_id' => $flight->id, 'seat_code' => '1A']);
        $this->post(route('bookings.store'), [
            'flight_id' => $flight->id, 'passengers' => ['Traveler B'], 'seat_codes' => ['1A'],
        ])->assertSessionHasErrors('seat_codes');
        $this->patch(route('bookings.cancel', $booking))->assertRedirect();
        $this->assertDatabaseMissing('booking_seats', ['flight_id' => $flight->id, 'seat_code' => '1A']);
    }

    public function test_wishlist_and_preferences_belong_to_current_user(): void
    {
        $flight = $this->flight();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('flights.wishlist', $flight))->assertRedirect();
        $this->get(route('account.wishlist'))->assertOk()->assertSee($flight->flight_number);
        $this->patch(route('account.preferences'), [
            'locale' => 'en', 'currency' => 'USD', 'timezone' => 'UTC',
            'seat_preference' => 'window', 'email_updates' => 1,
        ])->assertRedirect();
        $this->assertSame('en', $user->fresh()->locale);
        $this->assertTrue($user->fresh()->email_updates);
    }

    public function test_guest_can_browse_but_must_sign_in_to_reserve(): void
    {
        $flight = $this->flight();
        $this->get(route('flights.show', $flight))->assertOk();
        $this->get(route('bookings.create', $flight))->assertRedirect(route('login'));
        $this->get(route('help'))->assertOk();
        $this->get(route('info'))->assertOk();
        $this->get(route('legal'))->assertOk();
    }
}
