<?php

namespace Tests\Feature\Account;

use App\Helpers\Facades\Urler;
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
        $this->assertEquals($user->homepage, 'https://homepage.com');

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'homepage' => ''
        ]);
        $response->assertSessionHasNoErrors();
    }

    public function test_user_can_change_company()
    {
        $user = User::factory()->create();
        $name = $user->name;

        $company = $this->faker->text();
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'company' => $company
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals($company, $user->company);

        $company = str_repeat('x', 300);
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'company' => $company
        ]);
        $response->assertSessionHasErrors();
    }

    public function test_user_can_change_social_url($platform = 'facebook')
    {
        $user = User::factory()->create();
        $name = $user->name;

        $social_profile = $this->faker->userName();

        $field_name = $platform . '_profile';

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            $field_name => $social_profile
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals(Urler::sanitizeSocialMediaProfileUrl($platform, $social_profile), $user->getAttribute($field_name));

        $social_profile = str_repeat('x', 300);
        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            $field_name => $social_profile
        ]);
        $response->assertSessionHasErrors();
    }

    public function test_user_can_change_social_url_for_twitter()
    {
        $this->test_user_can_change_social_url('twitter');
    }

    public function test_user_can_change_social_url_for_linkedin()
    {
        $this->test_user_can_change_social_url('linkedin');
    }

    public function test_user_can_change_social_url_for_xing()
    {
        $this->test_user_can_change_social_url('xing');
    }

    public function test_user_can_change_tags()
    {
        $user = User::factory()->create();
        $name = $user->name;

        $about = $this->faker->text();

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
            'search_tags' => ['userTagA', 'userTagB'],
        ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertEquals(['userTagA', 'userTagB'], $user->tags->map(fn($t) => $t->name)->toArray());

        $response = $this->actingAs($user)->put(route('account.profile.update'), [
            'name' => $name,
        ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertEquals([], $user->tags->map(fn($t) => $t->name)->toArray());
    }    
}
