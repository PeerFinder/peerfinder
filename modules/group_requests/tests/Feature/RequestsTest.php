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

    public function test_user_can_render_requests_list()
    {
        $user = User::factory()->create();
        $group_request = GroupRequest::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('group_requests.index'));
        $response->assertStatus(200);
    }
}
