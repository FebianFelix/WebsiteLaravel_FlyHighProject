<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class BusinessFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Domain tests must not share throttling counters from other cases.
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    private function flight(): Flight
    {
        return Flight::create([
            'flight_number' => 'FH500', 'airline' => 'FlyHigh Air',
            'origin' => 'Jakarta', 'destination' => 'Bali',
            'departure_at' => now()->addDays(3), 'arrival_at' => now()->addDays(3)->addHours(2),
            'price' => 900000, 'seats_total' => 1, 'is_active' => true,
        ]);
    }

    public function test_idempotent_booking_prevents_duplicate_orders(): void
    {
        $flight = $this->flight();
        $user = User::factory()->create();
        $key = (string) Str::uuid();
        $payload = ['flight_id' => $flight->id, 'passengers' => ['Demo Passenger'], 'request_key' => $key];

        $this->actingAs($user)->post(route('bookings.store'), $payload)->assertRedirect();
        $this->post(route('bookings.store'), $payload)->assertRedirect();
        $this->assertSame(1, Booking::count());
        $this->assertSame(0, $flight->availableSeats());
    }

    public function test_demo_payment_is_idempotent_and_generates_ticket_and_pdf(): void
    {
        config(['flyhigh.demo_payment_enabled' => true]);
        Notification::fake();
        $flight = $this->flight();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('bookings.store'), [
            'flight_id' => $flight->id, 'passengers' => ['Traveler'],
        ])->assertRedirect();
        $booking = Booking::firstOrFail();
        $this->assertSame(Booking::PENDING, $booking->status);

        $this->post(route('bookings.demo-payment', $booking))->assertRedirect();
        $this->post(route('bookings.demo-payment', $booking))->assertRedirect();
        $this->assertSame(Booking::CONFIRMED, $booking->fresh()->status);
        $this->assertDatabaseCount('payments', 1);
        $this->get(route('bookings.ticket', $booking))->assertOk();
        $this->get(route('bookings.ticket.pdf', $booking))->assertOk()->assertHeader('Content-Type', 'application/pdf');

        $signed = URL::temporarySignedRoute('tickets.verify', now()->addMinutes(5), ['reference' => $booking->reference]);
        $this->get($signed)->assertOk()->assertSee($booking->reference);
        $this->get(route('tickets.verify', ['reference' => $booking->reference]))->assertForbidden();
    }

    public function test_expired_hold_releases_inventory_and_cannot_be_paid(): void
    {
        config(['flyhigh.demo_payment_enabled' => true]);
        $flight = $this->flight();
        $this->actingAs(User::factory()->create())->post(route('bookings.store'), [
            'flight_id' => $flight->id, 'passengers' => ['Traveler'],
        ])->assertRedirect();
        $booking = Booking::firstOrFail();
        $booking->update(['expires_at' => now()->subMinute()]);
        $this->assertSame(1, $flight->availableSeats());

        $this->post(route('bookings.demo-payment', $booking))->assertSessionHasErrors('payment');
        $this->artisan('flyhigh:expire-bookings')->assertExitCode(0);
        $this->assertSame(Booking::EXPIRED, $booking->fresh()->status);
    }

    public function test_other_customer_cannot_read_another_ticket_or_profile(): void
    {
        $flight = $this->flight();
        $owner = User::factory()->create();
        $this->actingAs($owner)->post(route('bookings.store'), [
            'flight_id' => $flight->id, 'passengers' => ['Traveler'],
        ])->assertRedirect();
        $booking = Booking::firstOrFail();
        $this->actingAs(User::factory()->create())
            ->get(route('bookings.ticket', $booking))->assertForbidden();
        $this->get(route('bookings.ticket.pdf', $booking))->assertForbidden();
    }

    public function test_project_attribution_headers_are_present(): void
    {
        $this->get(route('home'))->assertOk()
            ->assertHeader('X-Project-Origin', 'FlyHigh / FebianFelix');
    }
}
