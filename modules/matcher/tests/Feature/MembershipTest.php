<?php

namespace Matcher\Tests;

use App\Models\User;
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
        Matcher::addMemberToGroup($pg, $user3);
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
        $response->assertStatus(403);
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
        $response->assertStatus(403);
        $this->assertFalse($pg->isMember($user1));
    }

    public function test_user_cannot_join_completed_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        $pg->complete();

        $response = $this->actingAs($user1)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $response->assertStatus(403);
        $this->assertFalse($pg->isMember($user1));     

        $response = $this->actingAs($user2)->put(route('matcher.membership.store', ['pg' => $pg->groupname]));
        $response->assertStatus(403);
        $this->assertFalse($pg->isMember($user2));
    }

    public function test_user_can_edit_own_membership()
    {
        
    }

    public function test_user_cannot_edit_membership_of_other_users()
    {
        
    }

    public function test_user_can_delete_own_membership()
    {
        
    }

    public function test_user_cannot_delete_membership_of_other_users()
    {
        
    }

    public function test_owner_can_see_not_approved_users()
    {
        
    }
}