<?php

namespace Tests\Feature\Listeners;

use App\Listeners\CreateConversationForPeergroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Matcher\Models\Language;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Matcher\Facades\Matcher;

/**
 * @group listeners
 */
class PeergroupListenersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_conversation_created_with_peergroup()
    {
        $user = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user)->create();

        $this->assertDatabaseHas('conversations', ['conversationable_type' => Peergroup::class, 'conversationable_id' => $pg->id]);
    }

    public function test_conversation_deleted_with_peergroup()
    {
        $user = User::factory()->create();

        $pg1 = Peergroup::factory()->byUser($user)->create();
        $pg2 = Peergroup::factory()->byUser($user)->create();

        $pg1->delete();
        $pg2->delete();

        $this->assertDatabaseMissing('conversations', ['conversationable_type' => Peergroup::class, 'conversationable_id' => $pg1->id]);
        $this->assertDatabaseMissing('conversations', ['conversationable_type' => Peergroup::class, 'conversationable_id' => $pg2->id]);
    }

    public function test_user_added_to_conversation_when_joining_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user2);
        Matcher::addMemberToGroup($pg, $user3);

        $conversation = $pg->conversations()->first();

        $this->assertFalse($conversation->isParticipant($user1));
        $this->assertTrue($conversation->isParticipant($user2));
        $this->assertTrue($conversation->isParticipant($user3));
    }

    public function test_conversation_is_created_if_not_existent_when_joining_group()
    {
        $user1 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create();

        $pg->conversations()->first()->delete();

        Matcher::addMemberToGroup($pg, $user1);

        $this->assertTrue($pg->conversations()->exists());
    }

    public function test_user_removed_from_conversation_when_leaving_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user1)->create([
            'limit' => 5,
        ]);

        $m1 = Matcher::addMemberToGroup($pg, $user1);
        $m2 = Matcher::addMemberToGroup($pg, $user2);
        $m3 = Matcher::addMemberToGroup($pg, $user3);

        $m1->delete();
        $m2->delete();
        $m3->delete();

        $conversation = $pg->conversations()->first();

        $conversation->refresh();

        $this->assertFalse($conversation->isParticipant($user1));
        $this->assertFalse($conversation->isParticipant($user3));
        $this->assertFalse($conversation->isParticipant($user3));
    }    
}