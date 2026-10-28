<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class ReleaseQualityV2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_guest_can_explore_help_and_footer_but_cannot_book(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('Legal & privacy')
            ->assertSee('Original repository')->assertSee('FlyHigh v2.0.0');
        $this->get(route('flights.index'))->assertOk();
        $this->get(route('help'))->assertOk();
        $this->get(route('info'))->assertOk();
        $this->get(route('legal'))->assertOk();
        $this->get(route('itineraries.create'))->assertRedirect(route('login'));
    }

    public function test_control_center_exposes_only_role_appropriate_areas(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'athena']))
            ->get(route('admin.portal'))->assertOk()
            ->assertSee('Configuration & management')->assertSee('Dashboard & monitoring');
        $this->actingAs(User::factory()->create(['role'=>'hephaestus']))
            ->get(route('admin.portal'))->assertOk()
            ->assertSee('Configuration & management')->assertDontSee('Dashboard & monitoring');
        $this->actingAs(User::factory()->create(['role'=>'argus']))
            ->get(route('admin.portal'))->assertOk()
            ->assertDontSee('Configuration & management')->assertSee('Dashboard & monitoring');
    }

    public function test_live_stripe_keys_are_not_accepted_by_the_adapter(): void
    {
        config([
            'flyhigh.demo_mode'=>false,
            'flyhigh.payment_gateway'=>'stripe',
            'flyhigh.stripe_secret'=>'sk_live_this_is_intentionally_rejected',
            'flyhigh.stripe_webhook_secret'=>'whsec_fake',
        ]);
        $this->assertFalse(app(StripeGateway::class)->enabled());
        config(['flyhigh.stripe_secret'=>'sk_test_mock_for_tests']);
        $this->assertTrue(app(StripeGateway::class)->enabled());
        config(['flyhigh.demo_mode'=>true]);
        $this->assertFalse(app(StripeGateway::class)->enabled());
    }

    public function test_demo_has_no_indexing_metadata(): void
    {
        config(['flyhigh.demo_mode'=>true]);
        $this->get(route('home'))->assertOk()
            ->assertSee('noindex,nofollow,noarchive');
    }
}
