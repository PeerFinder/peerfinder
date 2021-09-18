<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Events\MemberJoinedPeergroup;
use Matcher\Events\MemberLeftPeergroup;
use Matcher\Events\PeergroupWasCreated;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;
use Matcher\Models\Membership;

/**
 * @group Peergroup
 */
class MembershipTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_can_join_group_throws_exceptions()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'limit' => 2,
        ]);

        $this->expectException(MembershipException::class);

        Matcher::addMemberToGroup($pg, $user1);
        Matcher::addMemberToGroup($pg, $user2);

        $this->assertFalse($pg->isOpen());

        Matcher::addMemberToGroup($pg, $user3);
    }

    public function test_join_private_group_throws_exceptions()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'private' => true,
        ]);

        $this->expectException(MembershipException::class);

        Matcher::addMemberToGroup($pg, $user2);
    }

    public function test_user_can_render_new_membership()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user1)->get(route('matcher.membership.create', ['pg' => $pg->groupname]));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route('matcher.membership.create', ['pg' => $pg->groupname]));
        $response->assertStatus(200);

        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user2)->get(route('matcher.membership.create', ['pg' => $pg->groupname]));
        $response->assertStatus(302);
    }

    public function test_user_can_join_a_group_without_approval()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $data = [
            'peergroup_id' => $pg->id,
            'user_id' => $user1->id,
            'comment' => $this->faker->text(),
        ];

        $response = $this->actingAs($user1)->put(route('matcher.membership.store', ['pg' => $pg->groupname]), $data);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('memberships', $data);
    }

    public function test_user_cannot_join_a_group_with_approval()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $this->actingAs($user1)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $this->assertTrue($pg->isMember($user1));

        $this->actingAs($user2)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $this->assertFalse($pg->isMember($user2));
        $this->assertDatabaseHas('memberships', ['peergroup_id' => $pg->id, 'user_id' => $user2->id, 'approved' => false]);
    }

    public function test_user_cannot_join_private_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'private' => true,
        ]);

        # Owner can join private group
        $response = $this->actingAs($user1)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $this->assertTrue($pg->isMember($user1));

        $response = $this->actingAs($user2)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $response->assertStatus(403);
        $this->assertFalse($pg->isMember($user2));
    }

    public function test_user_cannot_join_full_group()
    {
        $user1 = User::factory()->create();
        $users = User::factory(5)->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'limit' => $users->count(),
        ]);

        $users->each(function ($user) use ($pg) {
            Matcher::addMemberToGroup($pg, $user);
        });

        $response = $this->actingAs($user1)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $response->assertStatus(302);
        $this->assertFalse($pg->isMember($user1));
    }

    public function test_user_cannot_join_completed_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        $pg->complete();

        $response = $this->actingAs($user1)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $response->assertStatus(302);
        $this->assertFalse($pg->isMember($user1));

        $response = $this->actingAs($user2)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $response->assertStatus(302);
        $this->assertFalse($pg->isMember($user2));
    }

    public function test_user_can_delete_own_membership()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user2)->get(route('matcher.membership.delete', ['pg' => $pg->groupname]));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->delete(route('matcher.membership.destroy', ['pg' => $pg->groupname]));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('memberships', ['peergroup_id' => $pg->id, 'user_id' => $user2->id]);
    }

    public function test_peergroup_changes_state_when_membership_approved()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'limit' => 2,
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user1);
        $m2 = Matcher::addMemberToGroup($pg, $user2);

        $pg->refresh();
        $this->assertTrue($pg->isOpen());

        $m1->approve();
        $m2->approve();

        $pg->refresh();

        $this->assertTrue($pg->isFull());
        $this->assertFalse($pg->isOpen());
    }

    public function test_owner_can_see_not_approved_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'limit' => 2,
            'with_approval' => true,
        ]);

        Matcher::addMemberToGroup($pg, $user1);
        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user1)->get(route('matcher.show', ['pg' => $pg->groupname]));
        $response->assertStatus(200);

        $response->assertSee(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertSee(route('matcher.membership.decline', ['pg' => $pg->groupname, 'username' => $user2->username]));

        $response = $this->actingAs($user2)->get(route('matcher.show', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
        $response->assertDontSee(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user2->username]));
    }

    public function test_owner_can_approve_user_membership()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user1);
        $m2 = Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user2)->post(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertStatus(403);

        $response = $this->actingAs($user1)->post(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertStatus(302);

        $m2->refresh();
        $this->assertTrue($m2->approved);
    }

    public function test_owner_cannot_approve_user_membership_if_group_full()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
            'limit' => 2,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user1);
        $m2 = Matcher::addMemberToGroup($pg, $user2);
        $m3 = Matcher::addMemberToGroup($pg, $user3);

        $m1->approve();
        $m2->approve();

        $response = $this->actingAs($user1)->post(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user3->username]));
        $response->assertStatus(302);

        $m3->refresh();
        $this->assertFalse($m3->approved);
    }

    public function test_owner_can_decline_user_membership()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        Matcher::addMemberToGroup($pg, $user1);
        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user2)->post(route('matcher.membership.decline', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertStatus(403);

        $response = $this->actingAs($user1)->post(route('matcher.membership.decline', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('memberships', ['peergroup_id' => $pg->id, 'user_id' => $user2->id]);
    }

    public function test_owner_cannot_approve_user_membership_other_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user1);
        $m2 = Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user2)->post(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertStatus(403);

        $response = $this->actingAs($user1)->post(route('matcher.membership.approve', ['pg' => $pg->groupname, 'username' => $user2->username]));
        $response->assertStatus(302);

        $m2->refresh();
        $this->assertTrue($m2->approved);
    }

    public function test_user_can_edit_membership()
    {
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user1);

        $response = $this->actingAs($user1)->get(route('matcher.membership.edit', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
    }

    public function test_user_can_update_membership()
    {
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user1);

        $data = [
            'comment' => $this->faker->text(),
        ];

        $response = $this->actingAs($user1)->put(route('matcher.membership.update', ['pg' => $pg->groupname]), $data);

        $response->assertSessionDoesntHaveErrors();
        $m1->refresh();

        $this->assertEquals($m1->comment, $data['comment']);
    }

    public function test_user_cannot_update_membership_with_invalid_data()
    {
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user1);

        $data = [
            'comment' => Str::random(1000),
        ];

        $response = $this->actingAs($user1)->put(route('matcher.membership.update', ['pg' => $pg->groupname]), $data);

        $response->assertSessionHasErrors();
        $m1->refresh();

        $this->assertNotEquals($m1->comment, $data['comment']);
    }

    public function test_event_is_triggered_when_user_joins()
    {
        Event::fake(MemberJoinedPeergroup::class);

        $user1 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user1);

        Event::assertDispatched(MemberJoinedPeergroup::class, function (MemberJoinedPeergroup $event) use ($pg, $user1, $m1) {
            return ($event->pg->id == $pg->id) && ($event->user->id == $user1->id) && ($event->membership->id == $m1->id);
        });
    }

    public function test_event_is_triggered_when_user_gets_approved()
    {
        Event::fake(MemberJoinedPeergroup::class);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'with_approval' => true,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user2);

        Event::assertNotDispatched(MemberJoinedPeergroup::class);

        $m1->approve();

        Event::assertDispatched(MemberJoinedPeergroup::class, function (MemberJoinedPeergroup $event) use ($pg, $user2, $m1) {
            return ($event->pg->id == $pg->id) && ($event->user->id == $user2->id) && ($event->membership->id == $m1->id);
        });
    }

    public function test_event_is_triggered_when_user_leaves()
    {
        Event::fake(MemberLeftPeergroup::class);

        $user1 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user1);

        $m1->delete();

        Event::assertDispatched(MemberLeftPeergroup::class, function (MemberLeftPeergroup $event) use ($pg, $user1) {
            return ($event->pg->id == $pg->id) && ($event->user->id == $user1->id);
        });
    }    
}