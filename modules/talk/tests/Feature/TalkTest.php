<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Talk\Facades\Talk;
use Tests\TestCase;

/**
 * @group Talk
 */
class TalkTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_create_conversation_by_action()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $conversation = Talk::createConversation($user1, [$user1, $user2, $user3], [
            'title' => $this->faker->text(),
            'message' => $this->faker->text(),
        ]);

        $this->assertEquals(1, $user1->participated_conversations()->count());
        $this->assertEquals($user1->email, $conversation->users()->first()->email);
        $this->assertEquals($user1->email, $conversation->conversationable->email);
    }

    public function test_list_of_user_in_conversation()
    {
        $conversation = Conversation::factory()->byUser()->create();
        $users = User::factory(5)->create();

        $this->be($users->first());

        $conversation->addUser($users->get(0));

        $filtered_users = Talk::filterUsersForConversation($conversation);
        $this->assertCount(1, $filtered_users);

        $conversation->addUser($users->get(1));
        $conversation->addUser($users->get(2));

        $conversation->refresh();

        $this->assertStringContainsString($users->first()->name, Talk::usersAsString($filtered_users));
        
        $filtered_users = Talk::filterUsersForConversation($conversation);
        
        $ids = array_map(fn($user) => $user->id, $filtered_users);

        $this->assertNotContains($users->first()->id, $ids);
    }

    public function test_conversation_can_be_embedded()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser()->create();

        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $reply1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $content = Talk::embedConversation($conversation);

        $this->assertStringContainsString($reply1->message, $content);
    }
}
