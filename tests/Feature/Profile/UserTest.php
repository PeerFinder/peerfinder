<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group profile
 */
class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_profile_not_available_for_guests()
    {
        $response = $this->get(route('profile.user.show', ['user' => 'abcd']));
        $response->assertRedirect(route('login'));
    }

    public function test_user_profile_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => $user->username]));
        $response->assertStatus(200);
    }

    public function test_user_profile_not_found()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => 'qwerty']));
        $response->assertStatus(404);
    }    
}
