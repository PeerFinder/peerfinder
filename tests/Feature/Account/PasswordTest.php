<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group account
 */
class PasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_password_not_available_for_guests()
    {
        $response = $this->get(route('account.password.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_account_password_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.password.edit'));
        $response->assertStatus(200);
    }
}
