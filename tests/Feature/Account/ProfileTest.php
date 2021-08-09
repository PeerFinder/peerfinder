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

    public function test_user_can_change_slogan()
    {
        $user = User::factory()->create();
        $name = $user->name;

        $slogan = $this->faker->text();
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'slogan' => $slogan
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals($slogan, $user->slogan);

        $slogan = str_repeat('x', 300);
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'slogan' => $slogan
        ]);
        $response->assertSessionHasErrors();
    }

    public function test_user_can_change_about()
    {
        $user = User::factory()->create();
        $name = $user->name;

        $about = $this->faker->text();
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'about' => $about
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals($about, $user->about);
    }

    public function test_user_can_change_homepage()
    {
        $user = User::factory()->create();
        $name = $user->name;

        $url = $this->faker->url();
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'homepage' => $url
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals($url, $user->homepage);

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'homepage' => 'this-is-not-an-url'
        ]);
        $response->assertSessionHasErrors();

        $dummy = str_repeat('x', 300);
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'homepage' => $dummy
        ]);
        $response->assertSessionHasErrors();

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'homepage' => 'homepage.com'
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals($user->homepage, 'http://homepage.com');

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'homepage' => ''
        ]);
        $response->assertSessionHasNoErrors();
    }
}
