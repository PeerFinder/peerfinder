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
}
