<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Talk\Facades\Talk;
use Talk\Models\Receipt;
use Tests\TestCase;

/**
 * @group Talk
 */
class ReplyTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;


    public function test_user_can_store_reply_in_conversation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();
        $conversation->addUser($user1);

        $response = $this->actingAs($user1)->put(route('talk.reply.store', ['conversation' => $conversation->identifier]), [
            'message' => $this->faker->text(),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('replies', ['user_id' => $user1->id]);
    }

    public function test_user_cannot_send_empty_message()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)->put(route('talk.store.user', ['user' => $user2->username]), [
            'message' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    public function test_deleting_user_deletes_replies_and_receipts()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser()->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $input = [
            'message' => $this->faker->text(),
        ];

        Talk::createReply($conversation, $user1, $input);

        $this->assertDatabaseHas('replies', ['user_id' => $user1->id, 'conversation_id' => $conversation->id]);
        $this->assertDatabaseHas('receipts', ['user_id' => $user2->id, 'conversation_id' => $conversation->id]);

        $user2->delete();
        $this->assertDatabaseMissing('receipts', ['user_id' => $user2->id, 'conversation_id' => $conversation->id]);

        $user1->delete();
        $this->assertDatabaseMissing('replies', ['user_id' => $user1->id]);
    }

    public function test_deleting_conversation_deletes_replies_and_receipts()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $input = [
            'message' => $this->faker->text(),
        ];
        
        Talk::createReply($conversation, $user1, $input);
        
        $conversation->delete();

        $this->assertDatabaseMissing('receipts', ['conversation_id' => $conversation->id]);
        $this->assertDatabaseMissing('replies', ['conversation_id' => $conversation->id]);
    }

    public function test_reply_on_reply()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $reply1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);
        $reply2 = Talk::createReply($reply1, $user1, ['message' => $this->faker->text()]);

        $this->assertDatabaseHas('replies', ['reply_id' => $reply1->id]);
    }

    public function test_user_has_unread_conversations()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $this->assertFalse(Talk::userHasUnreadConversations($user2));

        $reply1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $user2 = User::whereId($user2->id)->with('receipts')->get()->first();

        $this->assertTrue(Talk::userHasUnreadConversations($user2));

        $this->assertEquals(route('talk.show', ['conversation' => $conversation->identifier, '#reply-' . $reply1->identifier]),
                    Talk::dynamicConversationsUrl($user2));
    }

    public function test_get_unread_conversations_for_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $this->assertNull(Talk::getRecentUnreadConversationForUser($user2));

        $reply1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $user2 = User::whereId($user2->id)->with('receipts')->get()->first();

        $unreadConversation = Talk::getRecentUnreadConversationForUser($user2);

        $this->assertNotNull($unreadConversation);
        $this->assertEquals($conversation->id, $unreadConversation->id);
    }
}