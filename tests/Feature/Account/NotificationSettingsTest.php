<?php

namespace Tests\Feature\Account;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
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

    public function test_account_notification_settings_cannot_be_set()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.notification_settings.update'), [
            NotificationSettingType::UnreadMessages->name => 5,
            NotificationSettingType::NewGroupsNewsletter->name => NotificationSettingStatus::Disabled->value,
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors();
    }    

    public function test_account_notification_settings_can_be_set()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('account.notification_settings.update'), [
            NotificationSettingType::UnreadMessages->name => NotificationSettingStatus::Mail->value,
            NotificationSettingType::NewGroupsNewsletter->name => NotificationSettingStatus::Disabled->value,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notification_settings', ['user_id' => $user->id, 'notification_type' => NotificationSettingType::UnreadMessages->value, 'notification_status' => NotificationSettingStatus::Mail]);
        $this->assertDatabaseHas('notification_settings', ['user_id' => $user->id, 'notification_type' => NotificationSettingType::NewGroupsNewsletter->value, 'notification_status' => NotificationSettingStatus::Disabled]);
    }
}
