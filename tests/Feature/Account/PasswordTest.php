<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
 
    public function test_user_can_change_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.password.update'), [
            'current_password' => 'password',
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('newPassword123', $user->password));
    }

    public function test_current_password_shall_be_correct()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123'
        ]);

        $response->assertSessionHasErrors();
        $this->assertFalse(Hash::check('newPassword123', $user->password));
    }    

    public function test_passwords_shall_match()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.password.update'), [
            'current_password' => 'password',
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword456'
        ]);

        $response->assertSessionHasErrors();
        $this->assertFalse(Hash::check('newPassword123', $user->password));
    }
}
