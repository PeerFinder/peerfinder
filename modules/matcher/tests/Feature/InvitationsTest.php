<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Facades\Matcher;
use Matcher\Models\Invitation;
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

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname]), [
            'search_users' => [],
        ]);

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

        $comment = $this->faker->text();

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname]), [
            'search_users' => [
                $user2->username,
                $user3->username,
                $user4->username,
            ],
            'comment' => $comment
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('invitations', ['receiver_user_id' => $user2->id, 'peergroup_id' => $pg->id, 'comment' => $comment]);
        $this->assertDatabaseHas('invitations', ['receiver_user_id' => $user3->id, 'peergroup_id' => $pg->id, 'comment' => $comment]);
        $this->assertDatabaseHas('invitations', ['receiver_user_id' => $user4->id, 'peergroup_id' => $pg->id, 'comment' => $comment]);

        $inv1 = Invitation::where('peergroup_id', $pg->id)->where('receiver_user_id', $user2->id)->first();
        $this->assertEquals($user1->id, $inv1->sender()->first()->id);
        $this->assertEquals($user2->id, $inv1->receiver()->first()->id);
        $this->assertEquals($pg->id, $inv1->peergroup()->first()->id);
    }

    public function test_no_invitation_for_group_members()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname]), [
            'search_users' => [
                $user1->username,
                $user2->username
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('invitations', ['receiver_user_id' => $user1->id, 'peergroup_id' => $pg->id]);
        $this->assertDatabaseMissing('invitations', ['receiver_user_id' => $user2->id, 'peergroup_id' => $pg->id]);
    }

    public function test_no_multiple_invitations_to_same_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user3);

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname]), [
            'search_users' => [
                $user2->username
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('invitations', 1);

        $response = $this->actingAs($user3)->put(route('matcher.invitations.store', ['pg' => $pg->groupname]), [
            'search_users' => [
                $user2->username
            ],
            'comment' => $this->faker->text(),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('invitations', 1);
    }

    public function test_invitation_deleted_when_user_joined_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user1)->put(route('matcher.invitations.store', ['pg' => $pg->groupname]), [
            'search_users' => [
                $user2->username
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('invitations', ['receiver_user_id' => $user2->id, 'peergroup_id' => $pg->id]);

        Matcher::addMemberToGroup($pg, $user2);

        $this->assertDatabaseMissing('invitations', ['receiver_user_id' => $user2->id, 'peergroup_id' => $pg->id]);
    }

    public function test_invitation_gets_deleted_with_sender()
    {
        
    }

    public function test_invitation_gets_deleted_with_receiver()
    {
        
    }    

    public function test_invitation_gets_deleted_with_peergroup()
    {
        
    }
}
