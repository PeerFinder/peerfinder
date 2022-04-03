<?php

namespace Tests\Feature\Helpers;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
use App\Helpers\Facades\NotificationCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_notification_enabled_for_non_existing_mandatory_notification()
    {
        $user = User::factory()->create();

        $this->assertTrue(NotificationCenter::notificationEnabled($user, NotificationSettingType::UnreadMessages));

        $this->assertDatabaseHas('notification_settings', ['user_id' => $user->id, 'notification_type' => NotificationSettingType::UnreadMessages->value]);
    }

    public function test_notification_enabled_for_non_existing_non_mandatory_notification()
    {
        $user = User::factory()->create();
        
        $this->assertFalse(NotificationCenter::notificationEnabled($user, NotificationSettingType::NewGroupsNewsletter));
    }

    public function test_notification_enabled_for_existing_notification()
    {
        $user = User::factory()->create();

        NotificationCenter::setNotificationSetting($user, NotificationSettingType::NewGroupsNewsletter, NotificationSettingStatus::Mail);
        
        $this->assertTrue(NotificationCenter::notificationEnabled($user, NotificationSettingType::NewGroupsNewsletter));

        NotificationCenter::setNotificationSetting($user, NotificationSettingType::NewGroupsNewsletter, NotificationSettingStatus::Disabled);
        
        $this->assertFalse(NotificationCenter::notificationEnabled($user, NotificationSettingType::NewGroupsNewsletter));
    }

    public function test_notification_settings_are_deleted_with_user()
    {
        $user = User::factory()->create();

        NotificationCenter::setNotificationSetting($user, NotificationSettingType::UnreadMessages, NotificationSettingStatus::Mail);
        NotificationCenter::setNotificationSetting($user, NotificationSettingType::NewGroupsNewsletter, NotificationSettingStatus::Mail);

        $this->assertDatabaseHas('notification_settings', ['user_id' => $user->id, 'notification_type' => NotificationSettingType::UnreadMessages->value]);
        $this->assertDatabaseHas('notification_settings', ['user_id' => $user->id, 'notification_type' => NotificationSettingType::NewGroupsNewsletter->value]);
        
        $user->delete();
        
        $this->assertDatabaseMissing('notification_settings', ['user_id' => $user->id, 'notification_type' => NotificationSettingType::UnreadMessages->value]);
        $this->assertDatabaseMissing('notification_settings', ['user_id' => $user->id, 'notification_type' => NotificationSettingType::NewGroupsNewsletter->value]);
    }
}
