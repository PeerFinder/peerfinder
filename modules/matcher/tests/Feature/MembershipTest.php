<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;

/**
 * @group Peergroup
 */
class MembershipTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

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
        
    }

    public function test_user_cannot_join_private_group()
    {
        
    }    

    public function test_user_cannot_join_full_group()
    {
        
    }

    public function test_user_cannot_join_completed_group()
    {
        
    }

    public function test_user_can_edit_own_membership()
    {
        
    }

    public function test_user_cannot_edit_membership_of_other_users()
    {
        
    }    
}