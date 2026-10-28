<?php

namespace Tests\Feature;

use App\Models\Flight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightFiltersV2Test extends TestCase
{
    use RefreshDatabase;

    public function test_multicity_search_filters_first_leg_to_intermediate_city(): void
    {
        $one = Flight::create([
            'flight_number'=>'FHF801','airline'=>'Demo Air','origin'=>'Jakarta',
            'destination'=>'Bali','departure_at'=>now()->addDays(5),
            'arrival_at'=>now()->addDays(5)->addHours(2), 'price'=>1000000,
            'cabin_class'=>'business','seats_total'=>30,'is_active'=>true,
        ]);
        $two = Flight::create([
            'flight_number'=>'FHF802','airline'=>'Demo Air','origin'=>'Bali',
            'destination'=>'Singapore','departure_at'=>now()->addDays(6),
            'arrival_at'=>now()->addDays(6)->addHours(2), 'price'=>2000000,
            'cabin_class'=>'business','seats_total'=>30,'is_active'=>true,
        ]);
        $date=now()->addDays(6)->toDateString();
        $this->get(route('flights.index', [
            'trip_type'=>'multi_city','origin'=>'Jakarta','destination'=>'Singapore',
            'via'=>'Bali','via_date'=>$date,'cabin_class'=>'business',
        ]))->assertOk()->assertSee('FHF801')->assertSee('FHF802');
        $this->get(route('flights.index',['cabin_class'=>'first']))->assertOk()
            ->assertDontSee('FHF801');
        $this->get(route('flights.show',$one))->assertOk()
            ->assertSee('Business')->assertSee('20 kg');
    }
}
