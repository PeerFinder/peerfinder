<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;
use Matcher\Events\PeergroupCreated;
use Matcher\Events\PeergroupDeleted;
use Matcher\Models\GroupType;
use Matcher\Models\Membership;
use App\Notifications\UserRequestsToJoinGroup;

/**
 * @group Peergroup
 */
class PoliciesTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_policy_edit_peergroup_by_owner()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('matcher.edit', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
        $response->assertSee($pg->title);
    }

    public function test_policy_edit_peergroup_by_co_owner()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user2);

        $m1->member_role_id = Membership::ROLE_CO_OWNER;
        $m1->save();

        $response = $this->actingAs($user2)->get(route('matcher.edit', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
        $response->assertSee($pg->title);
    }

    public function test_policy_only_owner_can_transfer_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user2);
        $m1->member_role_id = Membership::ROLE_CO_OWNER;
        $m1->save();

        $response = $this->actingAs($user2)->put(route('matcher.editOwner', ['pg' => $pg->groupname]), [
            'owner' => $user2->username,
        ]);

        $response->assertStatus(403);
        $pg->refresh();
        $this->assertEquals($pg->user()->first()->id, $user1->id);
    }

    public function test_policy_only_owner_can_delete_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user2);
        $m1->member_role_id = Membership::ROLE_CO_OWNER;
        $m1->save();

        $response = $this->actingAs($user2)->delete(route('matcher.delete', ['pg' => $pg->groupname]), [
            'confirm_delete' => '1'
        ]);

        $response->assertStatus(403);
    }

    public function test_policy_co_owner_can_approve_members()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user2);
        $m1->member_role_id = Membership::ROLE_CO_OWNER;
        $m1->approve();
        $m1->save();

        $m2 = Matcher::addMemberToGroup($pg, $user3);

        $response = $this->actingAs($user2)->post(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user3->username]));
        $response->assertStatus(302);
    }

    public function test_policy_notification_generated_when_user_requests_to_joins_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user2);
        $m1->member_role_id = Membership::ROLE_CO_OWNER;
        $m1->approve();

        foreach($user1->unreadNotifications()->get() as $notification) {
            $notification->markAsRead();
        };

        foreach($user2->unreadNotifications()->get() as $notification) {
            $notification->markAsRead();
        };

        Matcher::addMemberToGroup($pg, $user3);

        $notification = $user1->unreadNotifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals(UserRequestsToJoinGroup::class, $notification->type);
        $this->assertEquals($pg->id, $notification->data['peergroup_id']);
        $this->assertEquals($user3->name, $notification->data['user_name']);

        $notification = $user2->unreadNotifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals(UserRequestsToJoinGroup::class, $notification->type);
        $this->assertEquals($pg->id, $notification->data['peergroup_id']);
        $this->assertEquals($user3->name, $notification->data['user_name']);
    }

    public function test_co_owner_can_delete_members_membership()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user2);

        $m1 = Matcher::addMemberToGroup($pg, $user3);
        $m1->member_role_id = Membership::ROLE_CO_OWNER;
        $m1->approve();     
        
        $response = $this->actingAs($user3)->delete(route('matcher.membership.destroy', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('memberships', ['peergroup_id' => $pg->id, 'user_id' => $user2->id]);  
    }    
}