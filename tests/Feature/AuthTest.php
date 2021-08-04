<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;


/**
 * @group auth
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_see_the_login_form()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.auth.login');
    }

    /** @test */
    public function a_guest_can_see_the_register_form()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertViewIs('frontend.auth.register');
    }

    /** @test */
    public function a_user_can_login()
    {
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();

        $password = 'myLongPassword555&&';

        $user_data = [
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => $password
        ];

        $user = User::factory()->create([
            'password' => Hash::make($password)
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_guest_can_register()
    {
        $password = 'myLongPassword555&&';

        $user_data = [
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('register'), $user_data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $user = User::where('email', $user_data['email'])->first();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_guest_cannot_register_if_already_registered()
    {
        $user = User::factory()->create();

        $user_data = [
            'name' => 'dont care',
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];

        $response = $this->post(route('register'), $user_data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
