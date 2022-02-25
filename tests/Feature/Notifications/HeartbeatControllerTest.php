<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group heartbeat
 */
class HeartbeatControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_access_heartbeat_badges()
    {
        $user1 = User::factory()->create();

        $response = $this->actingAs($user1)->getJson(route('heartbeat.badges'));

        $response->assertStatus(200);
        $response->assertJson(['notifications' => 0]);
    }
}
