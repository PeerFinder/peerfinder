<?php

namespace Tests\Feature\Notifications;

use App\Enums\NotificationSettingStatus;
use App\Enums\NotificationSettingType;
use App\Helpers\Facades\NotificationCenter;
use App\Models\User;
use App\Notifications\GroupInvitationReceived;
use App\Notifications\NewMemberInGroup;
use App\Notifications\UserApprovedInGroup;
use App\Notifications\UserHasUnreadReplies;
use App\Notifications\UserRequestsToJoinGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Matcher\Facades\Matcher;
use Matcher\Models\Peergroup;
use Talk\Facades\Talk;
use Talk\Models\Conversation;
use Talk\Models\Receipt;
use Tests\TestCase;

/**
 * @group notifications
 */
class NotificationsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_notification_generated_when_user_joins_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user1);
        
        $notification = $user1->unreadNotifications()->first();
        $this->assertNull($notification);

        Matcher::addMemberToGroup($pg, $user2);

        $notification = $user1->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $this->assertEquals(NewMemberInGroup::class, $notification->type);
        $this->assertEquals($pg->id, $notification->data['peergroup_id']);
        $this->assertEquals($user2->name, $notification->data['user_name']);
    }

    public function test_notification_generated_when_user_requests_to_joins_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        Matcher::addMemberToGroup($pg, $user1);
        
        $notification = $user1->unreadNotifications()->first();
        $this->assertNull($notification);

        Matcher::addMemberToGroup($pg, $user2);

        $notification = $user1->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $this->assertEquals(UserRequestsToJoinGroup::class, $notification->type);
        $this->assertEquals($pg->id, $notification->data['peergroup_id']);
        $this->assertEquals($user2->name, $notification->data['user_name']);
    }

    public function test_notification_generated_when_user_is_approved()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user2);
        $m1->approve();

        $notification = $user2->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $this->assertEquals(UserApprovedInGroup::class, $notification->type);
        $this->assertEquals($pg->id, $notification->data['peergroup_id']);
        $this->assertEquals($user1->name, $notification->data['user_name']);
    }

    public function test_user_can_show_notifications()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user2);
        $m1->approve();

        $response = $this->actingAs($user1)->get(route('notifications.index'));
        $response->assertStatus(200);
        $response->assertSee(__('notifications/notifications.new_member_in_group_details', ['user_name' => $user2->name, 'title' => $pg->title]));
        $response->assertSee(__('notifications/notifications.user_requests_to_join_details', ['user_name' => $user2->name, 'title' => $pg->title]));

        $response = $this->actingAs($user2)->get(route('notifications.index'));
        $response->assertStatus(200);
        $response->assertSee(__('notifications/notifications.request_approved_details', ['user_name' => $user1->name, 'title' => $pg->title]));
    }

    public function test_talk_api_notifies_about_existing_receipts()
    {
        Notification::fake();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();

        $conversation->addUser($user2);
        $conversation->addUser($user1);

        $r1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $rc1 = Receipt::whereReplyId($r1->id)->whereUserId($user2->id)->first();
        $rc1->created_at = now()->subMinutes(50);
        $rc1->save();

        Talk::sendNotificationsForReceipts();

        Notification::assertSentTo([$user2], UserHasUnreadReplies::class);
    }

    public function test_talk_api_notifies_about_existing_receipts_only_when_notification_enabled()
    {
        Notification::fake();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        NotificationCenter::setNotificationSetting($user2, NotificationSettingType::UnreadMessages, NotificationSettingStatus::Disabled);

        $conversation = Conversation::factory()->byUser($user2)->create();

        $conversation->addUser($user2);
        $conversation->addUser($user1);

        $r1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $rc1 = Receipt::whereReplyId($r1->id)->whereUserId($user2->id)->first();
        $rc1->created_at = now()->subMinutes(50);
        $rc1->save();

        Talk::sendNotificationsForReceipts();

        Notification::assertNothingSentTo([$user2], UserHasUnreadReplies::class);
    }

    public function test_notification_generated_when_user_receives_group_invitation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user2)->create();

        $notification = $user1->unreadNotifications()->first();
        $this->assertNull($notification);

        $response = $this->actingAs($user2)->put(route('matcher.invitations.store', ['pg' => $pg->groupname]), [
            'search_users' => [
                $user1->username,
            ],
            'comment' => $this->faker->text(),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $notification = $user1->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $this->assertEquals(GroupInvitationReceived::class, $notification->type);
        $this->assertEquals($pg->id, $notification->data['peergroup_id']);
        $this->assertEquals($user2->id, $notification->data['user_id']);
    }
}
