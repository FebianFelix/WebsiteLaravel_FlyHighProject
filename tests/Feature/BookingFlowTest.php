<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private function flight(int $seats = 2): Flight
    {
        return Flight::create([
            'flight_number' => 'FH900',
            'airline' => 'FlyHigh Test',
            'origin' => 'Jakarta',
            'destination' => 'Bali',
            'departure_at' => now()->addDays(3),
            'arrival_at' => now()->addDays(3)->addHours(2),
            'price' => 1000000,
            'seats_total' => $seats,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_book(): void
    {
        $flight = $this->flight();
        $this->post(route('bookings.store'), [
            'flight_id' => $flight->id,
            'passengers' => ['Passenger'],
        ])->assertRedirect(route('login'));
    }

    public function test_member_can_book_and_cancel(): void
    {
        $flight = $this->flight();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'flight_id' => $flight->id,
            'passengers' => ['First Guest', 'Second Guest'],
        ]);

        $booking = Booking::firstOrFail();
        $response->assertRedirect(route('bookings.show', $booking));
        $this->assertSame(2, $booking->passengers()->count());
        $this->assertSame(2000000, $booking->total_price);
        $this->assertSame(0, $flight->availableSeats());

        $this->patch(route('bookings.cancel', $booking))->assertRedirect(route('bookings.show', $booking));
        $this->assertSame('cancelled', $booking->fresh()->status);
        $this->assertSame(2, $flight->availableSeats());
    }

    public function test_inventory_and_ownership_are_enforced(): void
    {
        $flight = $this->flight(1);
        $owner = User::factory()->create();
        $another = User::factory()->create();

        $this->actingAs($owner)->post(route('bookings.store'), [
            'flight_id' => $flight->id, 'passengers' => ['One'],
        ])->assertRedirect();

        $booking = Booking::firstOrFail();

        $this->actingAs($another)->post(route('bookings.store'), [
            'flight_id' => $flight->id, 'passengers' => ['Two'],
        ])->assertSessionHasErrors('passengers');

        $this->get(route('bookings.show', $booking))->assertForbidden();
        $this->patch(route('bookings.cancel', $booking))->assertForbidden();
    }

    public function test_admin_can_manage_flights(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.flights.store'), [
            'flight_number' => 'FH901',
            'airline' => 'FlyHigh Test',
            'origin' => 'Jakarta',
            'destination' => 'Singapore',
            'departure_at' => now()->addDays(4)->format('Y-m-d H:i:s'),
            'arrival_at' => now()->addDays(4)->addHours(2)->format('Y-m-d H:i:s'),
            'price' => 2000000,
            'seats_total' => 40,
            'is_active' => 1,
        ])->assertRedirect(route('admin.flights.index'));

        $this->assertDatabaseHas('flights', ['flight_number' => 'FH901']);
    }
}
