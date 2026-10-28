<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\TestCase;

class ItineraryV2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    private function flights(): array
    {
        $one = Flight::create([
            'flight_number'=>'LEG201','airline'=>'FlyHigh',
            'origin'=>'Jakarta','destination'=>'Bali',
            'departure_at'=>now()->addDays(7),'arrival_at'=>now()->addDays(7)->addHours(2),
            'price'=>500000,'seats_total'=>1,'is_active'=>true,
        ]);
        $two = Flight::create([
            'flight_number'=>'LEG202','airline'=>'FlyHigh',
            'origin'=>'Bali','destination'=>'Singapore',
            'departure_at'=>now()->addDays(8),'arrival_at'=>now()->addDays(8)->addHours(3),
            'price'=>800000,'seats_total'=>1,'is_active'=>true,
        ]);
        return [$one,$two];
    }

    public function test_connected_itinerary_is_atomic_and_can_be_confirmed(): void
    {
        config(['flyhigh.demo_payment_enabled'=>true]);
        [$first,$second]=$this->flights();
        $user=User::factory()->create();
        $key=(string)Str::uuid();
        $payload=['flight_ids'=>[$first->id,$second->id,''], 'passengers'=>['Sample Traveler'],'request_key'=>$key];
        $this->actingAs($user)->post(route('itineraries.store'),$payload)->assertRedirect();
        $itinerary=Itinerary::firstOrFail();
        $this->assertSame(1300000,$itinerary->total_price);
        $this->assertSame(2,$itinerary->bookings()->count());
        $this->assertSame(0,$first->availableSeats());
        $this->assertSame(0,$second->availableSeats());
        $this->post(route('itineraries.store'),$payload)->assertRedirect();
        $this->assertDatabaseCount('itineraries',1);
        $this->post(route('itineraries.confirm',$itinerary))->assertRedirect();
        $this->assertSame('confirmed',$itinerary->fresh()->status);
        $this->assertSame(2,Booking::where('status','confirmed')->count());
        $this->patch(route('itineraries.cancel',$itinerary))->assertRedirect();
        $this->assertSame(1,$first->availableSeats());
        $this->assertSame(1,$second->availableSeats());
    }

    public function test_itinerary_rejects_disconnected_segments_without_holds(): void
    {
        [$first,$second]=$this->flights();
        $second->update(['origin'=>'Surabaya']);
        $this->actingAs(User::factory()->create())->post(route('itineraries.store'),[
            'flight_ids'=>[$first->id,$second->id],
            'passengers'=>['Sample Traveler'],
            'request_key'=>(string)Str::uuid(),
        ])->assertSessionHasErrors('flight_ids');
        $this->assertDatabaseCount('itineraries',0);
        $this->assertDatabaseCount('bookings',0);
    }

    public function test_itinerary_rejects_other_user_and_single_leg_actions(): void
    {
        [$first,$second]=$this->flights();
        $owner=User::factory()->create();
        $this->actingAs($owner)->post(route('itineraries.store'),[
            'flight_ids'=>[$first->id,$second->id],
            'passengers'=>['A Traveler'],'request_key'=>(string)Str::uuid(),
        ])->assertRedirect();
        $itinerary=Itinerary::firstOrFail();
        $booking=$itinerary->bookings()->firstOrFail();
        $this->patch(route('bookings.cancel',$booking))->assertStatus(409);
        $this->actingAs(User::factory()->create())->get(route('itineraries.show',$itinerary))->assertForbidden();
    }
}
