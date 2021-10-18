<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use App\Notifications\NewMemberInGroup;
use App\Notifications\UserApprovedInGroup;
use App\Notifications\UserRequestsToJoinGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Facades\Matcher;
use Matcher\Models\Peergroup;
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
}
