<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLastSeenTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_last_seen_time_saved()
    {
        $user = User::factory()->create();

        $this->assertNull($user->last_seen);

        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => $user->username]));

        $response->assertStatus(200);

        $user->refresh();

        $this->assertNotNull($user->last_seen);
    }
}
