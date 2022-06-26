<?php

namespace GroupRequests\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use GroupRequests\Models\GroupRequest;
use Tests\TestCase;

/**
 * @group GroupRequests
 */
class GroupRequestsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_guest_cannot_render_group_requests()
    {
        $response = $this->get(route('group_requests.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_render_group_requests_list()
    {
        $user = User::factory()->create();
        $group_request = GroupRequest::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('group_requests.index'));
        $response->assertStatus(200);

        $response->assertSee($group_request->title);
    }

    public function test_user_can_create_group_request()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('group_requests.create'));

        $response->assertStatus(200);
    }
}
