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
class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_email_not_available_for_guests()
    {
        $response = $this->get(route('account.email.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_account_email_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.email.edit'));
        $response->assertStatus(200);
    }
 
    public function test_user_can_change_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.email.update'), [
            'email' => 'mail@new.com'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('mail@new.com', $user->email));
    }
}
