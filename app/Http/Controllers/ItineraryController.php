<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Itinerary;
use App\Models\SiteSetting;
use App\Services\ItineraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ItineraryController extends Controller
{
    public function __construct(private readonly ItineraryService $service) {}
    public function create(): View
    {
        abort_unless(config('flyhigh.booking_mode') === 'simulation', 503, 'Synthetic bookings are unavailable in catalog-only mode.');
        abort_unless(SiteSetting::valueFor('booking_enabled') === '1', 503);
        return view('itineraries.create', [
            'flights'=>Flight::where('is_active',true)->where('departure_at','>',now())->orderBy('departure_at')->limit(100)->get(),
            'key'=>(string) Str::uuid(),
        ]);
    }
    public function store(Request $request): RedirectResponse
    {
        abort_unless(config('flyhigh.booking_mode') === 'simulation', 503, 'Synthetic bookings are unavailable in catalog-only mode.');
        abort_unless(SiteSetting::valueFor('booking_enabled') === '1', 503);
        $request->merge(['flight_ids'=>array_values(array_filter((array)$request->input('flight_ids',[]), fn($v)=> $v !== null && $v !== ''))]);
        $data=$request->validate([
            'flight_ids'=>['required','array','min:2','max:4'],
            'flight_ids.*'=>['required','integer','distinct','exists:flights,id'],
            'passengers'=>['required','array','min:1','max:6'],
            'passengers.*'=>['required','string','max:120'],
            'request_key'=>['required','uuid'],
        ]);
        $itinerary=$this->service->reserve($request->user(),array_map('intval',$data['flight_ids']),$data['passengers'],$data['request_key']);
        return redirect()->route('itineraries.show',$itinerary)->with('success','Itinerary demo created.');
    }
    public function show(Request $request, Itinerary $itinerary): View
    {
        abort_unless($itinerary->user_id === $request->user()->id || $request->user()->canMonitor(),403);
        return view('itineraries.show',['itinerary'=>$itinerary->load('bookings.flight')]);
    }
    public function confirm(Request $request, Itinerary $itinerary): RedirectResponse
    {
        abort_unless($itinerary->user_id === $request->user()->id,403);
        abort_unless(config('flyhigh.demo_payment_enabled'),404);
        $this->service->confirmDemo($itinerary);
        return back()->with('success','All segments confirmed in simulation. No real payment was processed.');
    }
    public function cancel(Request $request, Itinerary $itinerary): RedirectResponse
    {
        abort_unless($itinerary->user_id === $request->user()->id,403);
        $this->service->cancel($itinerary);
        return back()->with('success','Itinerary cancelled.');
    }
}
