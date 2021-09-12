<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;

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

        $response = $this->actingAs($user1)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('memberships', ['peergroup_id' => $pg->id, 'user_id' => $user1->id]);
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
}