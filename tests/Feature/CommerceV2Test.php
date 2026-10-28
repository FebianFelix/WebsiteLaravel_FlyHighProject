<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\PromoCode;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CommerceV2Test extends TestCase
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
            'flight_number'=>'COM200','airline'=>'FlyHigh Demo','origin'=>'Bali','destination'=>'Jakarta',
            'departure_at'=>now()->addDays(10), 'arrival_at'=>now()->addDays(10)->addHours(2),
            'price'=>1000000,'seats_total'=>8,'is_active'=>true,
        ]);
    }
    public function test_discount_is_applied_and_invoice_can_be_downloaded(): void
    {
        config(['flyhigh.demo_payment_enabled'=>true]);
        PromoCode::create(['code'=>'WELCOME10','type'=>'percentage','value'=>10,'max_uses'=>1,'is_active'=>true]);
        $flight=$this->flight();
        $this->actingAs(User::factory()->create())->post(route('bookings.store'),[
            'flight_id'=>$flight->id, 'passengers'=>['Guest'], 'promo_code'=>'WELCOME10'
        ])->assertRedirect();
        $booking=Booking::firstOrFail();
        $this->assertSame(100000, $booking->discount_amount);
        $this->assertSame(900000, $booking->total_price);
        $this->get(route('bookings.invoice',$booking))->assertOk()->assertSee('WELCOME10');
        $this->get(route('bookings.invoice.pdf',$booking))->assertOk()->assertHeader('Content-Type','application/pdf');
        $this->post(route('bookings.demo-payment',$booking))->assertRedirect();
        $this->assertSame('confirmed',$booking->fresh()->status);
        $this->post(route('bookings.refund',$booking),['reason'=>'Request to cancel a demo booking'])->assertRedirect();
        $refund=RefundRequest::firstOrFail();
        $this->assertSame('requested',$refund->status);
        $this->actingAs(User::factory()->create(['role'=>'argus']))->post(route('admin.refunds.approve',$refund))->assertForbidden();
        $this->actingAs(User::factory()->create(['role'=>'athena']))->post(route('admin.refunds.approve',$refund))->assertRedirect();
        $this->assertSame('refunded',$refund->fresh()->status);
    }
    public function test_promo_quota_is_enforced(): void
    {
        PromoCode::create(['code'=>'ONEUSE','type'=>'fixed','value'=>50000,'max_uses'=>1,'is_active'=>true]);
        $flight=$this->flight();
        $this->actingAs(User::factory()->create())->post(route('bookings.store'),[
            'flight_id'=>$flight->id,'passengers'=>['One'],'promo_code'=>'ONEUSE'
        ])->assertRedirect();
        $this->actingAs(User::factory()->create())->post(route('bookings.store'),[
            'flight_id'=>$flight->id,'passengers'=>['Two'],'promo_code'=>'ONEUSE'
        ])->assertSessionHasErrors('promo_code');
    }
    public function test_stripe_webhook_requires_signature_and_is_idempotent(): void
    {
        config([
            'flyhigh.payment_gateway'=>'stripe',
            'flyhigh.stripe_secret'=>'sk_test_fake',
            'flyhigh.stripe_webhook_secret'=>'whsec_test',
        ]);
        $flight=$this->flight();
        $user=User::factory()->create();
        $this->actingAs($user)->post(route('bookings.store'),[
            'flight_id'=>$flight->id,'passengers'=>['Stripe Test']
        ])->assertRedirect();
        $booking=Booking::firstOrFail();
        Http::fake(['api.stripe.com/*'=>Http::response([
            'id'=>'cs_test_flyhigh_1','url'=>'https://checkout.stripe.com/c/pay/cs_test_flyhigh_1',
        ],200)]);
        $this->post(route('bookings.stripe-checkout',$booking))->assertRedirect();
        $this->assertSame('cs_test_flyhigh_1',$booking->fresh()->stripe_session_id);
        $event=['id'=>'evt_flyhigh_1','type'=>'checkout.session.completed','data'=>['object'=>[
            'id'=>'cs_test_flyhigh_1','payment_status'=>'paid','currency'=>'idr',
            'metadata'=>['booking_id'=>$booking->id],
            'amount_total'=>1000000,'payment_intent'=>'pi_fake_1',
        ]]];
        $payload=json_encode($event);
        $timestamp=time();
        $header='t='.$timestamp.',v1='.hash_hmac('sha256',$timestamp.'.'.$payload,'whsec_test');
        $url=route('stripe.webhook');
        $this->call('POST',$url,[],[],[],['CONTENT_TYPE'=>'application/json'], $payload)->assertStatus(400);
        $this->call('POST',$url,[],[],[],['CONTENT_TYPE'=>'application/json','HTTP_STRIPE_SIGNATURE'=>$header], $payload)->assertOk();
        $this->assertSame('confirmed',$booking->fresh()->status);
        $this->assertDatabaseCount('payments',1);
        $this->withHeaders(['Stripe-Signature'=>$header,'Content-Type'=>'application/json'])
            ->call('POST',$url,[],[],[],['CONTENT_TYPE'=>'application/json'], $payload)->assertOk();
        $this->assertDatabaseCount('payments',1);
    }
}
