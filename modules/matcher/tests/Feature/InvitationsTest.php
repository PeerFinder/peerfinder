<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;
use Matcher\Models\Peergroup;
use Tests\TestCase;

/**
 * @group Peergroup
 */
class GroupInvitationsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_non_member_cannot_render_new_group_invitation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user2)->get(route('matcher.invitations.create', ['pg' => $pg->groupname]));

        $response->assertStatus(403);
    }

    public function test_user_can_render_new_group_invitation()
    {
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user1)->get(route('matcher.invitations.create', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
    }

    public function test_user_cannot_send_invitation_without_user_ids()
    {
        $user1 = User::factory()->create();
        
        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname], [
            'search_users' => [],
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname], []));

        $response->assertStatus(302);
        $response->assertSessionHasErrors();        
    }

    public function test_user_can_send_invitation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();
        
        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname], [
            'search_users' => [
                $user2->username,
                $user3->username,
                $user4->username,
            ],
            'comment' => $this->faker->text()
        ]));

        $response->assertStatus(302);
        $this->assertDatabaseHas('invitations', ['receiver_user_id' => $user2->username, 'peergroup_id' => $pg->groupname]);
    }
}
