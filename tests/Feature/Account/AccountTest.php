<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @group account
 */
class AccountTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_account_not_available_for_guests()
    {
        $response = $this->get(route('account.account.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_account_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.account.edit'));
        $response->assertStatus(200);
    }

    public function test_account_index_redirects_to_profile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.index'));
        $response->assertRedirect(route('account.profile.edit'));
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('account.account.destroy'), [
            'password' => 'password'
        ]);

        $response->assertRedirect(route('index'));
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => $user->email]);
    }

    public function test_user_cannot_delete_account_with_wrong_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('account.account.destroy'), [
            'password' => 'wrong-password'
        ]);
        
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }    
}
