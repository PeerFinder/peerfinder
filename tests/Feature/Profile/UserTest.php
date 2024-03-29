<?php

namespace Tests\Feature\Profile;

use App\Helpers\Facades\Urler;
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

    public function test_user_profile_redirects_to_current_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile.user.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('profile.user.show', ['user' => $user->username]));
    }

    public function test_user_profile_not_found()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => 'qwerty']));
        $response->assertStatus(404);
    }

    public function test_can_see_user_details()
    {
        $data = [
            'homepage' => $this->faker->url(),
            'slogan' => $this->faker->text(),
            'about' => $this->faker->text(),
            'twitter_profile' => Urler::sanitizeSocialMediaProfileUrl('twitter', $this->faker->userName()),
            'linkedin_profile' => Urler::sanitizeSocialMediaProfileUrl('linkedin', $this->faker->userName()),
            'facebook_profile' => Urler::sanitizeSocialMediaProfileUrl('facebook', $this->faker->userName()),
            'xing_profile' => Urler::sanitizeSocialMediaProfileUrl('xing', $this->faker->userName()),
        ];

        $user = User::factory()->create($data);

        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => $user->username]));

        $response->assertSee($data['homepage']);
        $response->assertSee($data['slogan']);
        $response->assertSee($data['about']);
        $response->assertSee($data['twitter_profile']);
        $response->assertSee($data['linkedin_profile']);
        $response->assertSee($data['facebook_profile']);
        $response->assertSee($data['xing_profile']);
    }

    public function test_user_can_search_for_users_as_json()
    {
        $user1 = User::factory()->create();
        $users = User::factory(10)->create();

        $response = $this->actingAs($user1)->getJson(route('profile.user.search', ['name' => $users[0]->name]));

        $response->assertStatus(200);
        $response->assertJson(['users' => []]);
    }

    public function test_user_can_search_for_users()
    {
        $user1 = User::factory()->create();
        $users = User::factory(10)->create();

        $response = $this->actingAs($user1)->get(route('profile.user.search', ['search' => $users[0]->name]));

        $response->assertStatus(200);
        $response->assertSee(__('profile/search.results'));
        $response->assertSee($users[0]->slogan);
        
        $response = $this->actingAs($user1)->get(route('profile.user.search', ['search' => '']));
        $response->assertStatus(200);
        $response->assertDontSee(__('profile/search.results'));
    }
}
