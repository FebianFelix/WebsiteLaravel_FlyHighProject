<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LiveOffersV2Test extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }
    public function test_external_search_is_off_by_default_and_demo_remains_available(): void
    {
        config(['flyhigh.flight_provider' => 'disabled', 'flyhigh.duffel_access_token' => null]);
        $this->get(route('flights.live.index'))->assertOk()->assertSee('Provider not configured');
        $this->post(route('flights.live.search'), [
            'origin'=>'CGK', 'destination'=>'DPS','date'=>now()->addDays(9)->format('Y-m-d'),
            'trip_type'=>'one_way','passengers'=>1,'cabin_class'=>'economy',
        ])->assertStatus(503);
    }
    public function test_external_search_uses_v2_offer_requests_without_order_creation(): void
    {
        config(['flyhigh.flight_provider' => 'duffel','flyhigh.duffel_access_token'=>'duffel_test_fake']);
        Http::fake(['api.duffel.com/*' => Http::response(['data'=>['offers'=>[[
            'total_amount'=>'122.00','total_currency'=>'USD','slices'=>[[
                'origin'=>['name'=>'Jakarta'], 'destination'=>['name'=>'Bali'],
                'segments'=>[['operating_carrier'=>['name'=>'Test Airline']]]
            ]]
        ]]]],200)]);
        $this->post(route('flights.live.search'),[
            'origin'=>'CGK','destination'=>'DPS','date'=>now()->addDays(9)->format('Y-m-d'),
            'trip_type'=>'one_way','passengers'=>1,'cabin_class'=>'economy',
        ])->assertOk()->assertSee('Test Airline');
        Http::assertSent(fn ($r) => str_contains($r->url(),'/air/offer_requests') && $r->method()==='POST');
    }
}
