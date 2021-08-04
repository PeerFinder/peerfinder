<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

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
            'email' => Str::random(10).'@gmail.com',
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

}
