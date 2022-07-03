<?php

namespace GroupRequests\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use GroupRequests\Models\GroupRequest;
use Matcher\Models\Language;
use Tests\TestCase;

/**
 * @group GroupRequests
 */
class GroupRequestsConversationsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_group_request_creates_and_deletes_conversation()
    {
        $user = User::factory()->create();
        $group_request = GroupRequest::factory()->byUser($user)->create();

        $this->assertDatabaseHas('conversations', [
            'conversationable_type' => GroupRequest::class,
            'conversationable_id' => $group_request->id,
        ]);

        $group_request->delete();

        $this->assertDatabaseMissing('conversations', [
            'conversationable_type' => GroupRequest::class,
            'conversationable_id' => $group_request->id,
        ]);
    }
}
