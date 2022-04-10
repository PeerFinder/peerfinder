<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @group settings
 */
class SettingsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_account_settings_not_available_for_guests()
    {
        $response = $this->get(route('account.settings.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_account_settings_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.settings.edit'));
        $response->assertStatus(200);
    }
 
    public function test_user_can_change_locale()
    {
        $user = User::factory()->create([
            'locale' => 'en',
        ]);

        $response = $this->actingAs($user)->put(route('account.settings.update'), [
            'locale' => 'de',
            'timezone' => $this->faker->timezone(),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('de', $user->locale);
    }

    public function test_user_cannot_change_unsupported_locale()
    {
        $user = User::factory()->create([
            'locale' => 'en',
        ]);

        $response = $this->actingAs($user)->put(route('account.settings.update'), [
            'locale' => 'xy',
            'timezone' => $this->faker->timezone(),
        ]);

        $response->assertSessionHasErrors();
        $this->assertEquals('en', $user->locale);
    }

    public function test_user_can_change_timezone()
    {
        $user = User::factory()->create([
            'timezone' => 'UTC',
        ]);

        $tz = $this->faker->timezone();

        $response = $this->actingAs($user)->put(route('account.settings.update'), [
            'locale' => 'de',
            'timezone' => $tz,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals($tz, $user->timezone);
    }
}
