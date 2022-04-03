<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @group notification_settings
 */
class NotificationSettingsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_account_notification_settings_not_available_for_guests()
    {
        $response = $this->get(route('account.notification_settings.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_account_notification_settings_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('account.notification_settings.edit'));
        $response->assertStatus(200);
    }
}
