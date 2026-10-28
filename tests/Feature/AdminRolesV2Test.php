<?php

namespace Tests\Feature;

use App\Models\ContentPage;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRolesV2Test extends TestCase
{
    use RefreshDatabase;

    public function test_roles_enforce_separate_permissions_at_server(): void
    {
        $hephaestus = User::factory()->create(['role' => User::HEPHAESTUS]);
        $argus = User::factory()->create(['role' => User::ARGUS]);
        $athena = User::factory()->create(['role' => User::ATHENA]);
        $guest = User::factory()->create();
        $this->actingAs($hephaestus)->get(route('admin.portal'))->assertOk();
        $this->get(route('admin.settings.index'))->assertOk();
        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($argus)->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.settings.index'))->assertForbidden();
        $this->actingAs($athena)->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.settings.index'))->assertOk();
        $this->actingAs($guest)->get(route('admin.portal'))->assertForbidden();
    }

    public function test_hephaestus_can_manage_public_content_but_argus_cannot(): void
    {
        $author = User::factory()->create(['role' => User::HEPHAESTUS]);
        $this->actingAs($author)->post(route('admin.pages.store'), [
            'slug' => 'sample-policy', 'title' => 'Sample Policy',
            'body' => '<script>alert(1)</script>', 'is_published' => '1',
        ])->assertRedirect(route('admin.pages.index'));
        $this->get(route('pages.show', 'sample-policy'))->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false);
        $this->actingAs(User::factory()->create(['role' => User::ARGUS]))
            ->get(route('admin.pages.index'))->assertForbidden();
    }

    public function test_settings_are_whitelisted_and_logged(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ATHENA]))
            ->patch(route('admin.settings.update'), [
                'brand_name' => 'FlyHigh Premium',
                'tagline' => 'Discover more',
                'support_email' => 'help@example.test',
                'support_hours' => 'Always',
                'booking_enabled' => '1',
                'banner_message' => 'Hello',
            ])->assertRedirect();
        $this->assertSame('FlyHigh Premium', SiteSetting::valueFor('brand_name'));
        $this->assertDatabaseHas('audit_events', ['action' => 'settings.updated']);
    }
}
