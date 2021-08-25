<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group auth
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.auth.register');
    }

    public function test_new_users_can_register()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'timezone' => $this->faker->timezone(),
        ];

        $response = $this->post(route('register'), $data);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals($data['timezone'], $user->timezone);
    }
}
