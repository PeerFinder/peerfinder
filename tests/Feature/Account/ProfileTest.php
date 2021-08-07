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
class ProfileTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_account_profile_not_available_for_guests()
    {
        $response = $this->get(route('account.profile.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_account_profile_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.profile.edit'));
        $response->assertStatus(200);
    }
 
    public function test_user_can_change_name()
    {
        $user = User::factory()->create();

        $name = $this->faker->name();

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals($name, $user->name);

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => ''
        ]);
        
        $response->assertSessionHasErrors();
    }
}
