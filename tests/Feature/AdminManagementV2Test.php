<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementV2Test extends TestCase
{
    use RefreshDatabase;

    public function test_athena_manages_roles_and_argus_cannot_escalate(): void
    {
        $athena=User::factory()->create(['role'=>User::ATHENA]);
        $member=User::factory()->create();
        $this->actingAs($athena)->get(route('admin.users.index'))->assertOk();
        $this->patch(route('admin.users.role',$member),['role'=>User::ARGUS])->assertRedirect();
        $this->assertSame(User::ARGUS,$member->fresh()->role);
        $this->assertDatabaseHas('audit_events',['action'=>'user.role.changed']);
        $this->actingAs($member->fresh())->patch(route('admin.users.role',$athena),['role'=>User::ARGUS])->assertForbidden();
        $this->get(route('admin.audit.index'))->assertOk();
    }

    public function test_athena_cannot_demote_self_or_last_admin(): void
    {
        $athena=User::factory()->create(['role'=>User::ATHENA]);
        $this->actingAs($athena)->patch(route('admin.users.role',$athena),['role'=>'member'])
            ->assertSessionHasErrors('role');
        $this->assertSame(User::ATHENA,$athena->fresh()->role);
    }
}
