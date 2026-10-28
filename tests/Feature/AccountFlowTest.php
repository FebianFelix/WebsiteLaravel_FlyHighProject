<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_security_and_token_management(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword123')]);
        $this->actingAs($user)->get(route('account.edit'))->assertOk();

        $this->post(route('account.tokens.create'), [
            'name' => 'Portfolio Tester', 'current_password' => 'oldpassword123',
        ])->assertRedirect();
        $this->assertCount(1, $user->tokens);

        $this->put(route('account.password'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertRedirect();
        $this->assertCount(0, $user->fresh()->tokens);
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_signed_verification_cannot_be_forged(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)
            ->get(route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]))
            ->assertForbidden();
    }
}
