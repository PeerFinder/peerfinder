<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Matcher\Models\Peergroup;
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

        $response = $this->actingAs($user1)->put(route('talk.replies.store', ['conversation' => $conversation->identifier]), [
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
        $reply2 = Talk::createReply($conversation, $user1, ['reply_message' => $this->faker->text(), 'reply' => $reply1->identifier]);

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

        $this->assertEquals(route('talk.show', ['conversation' => $conversation->identifier]), Talk::dynamicConversationsUrl($user2));
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

    public function test_user_can_store_reply_to_reply()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();
        $conversation->addUser($user1);

        $r1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $response = $this->actingAs($user1)->put(route('talk.replies.store', ['conversation' => $conversation->identifier]), [
            'reply_message' => $this->faker->text(),
            'reply' => $r1->identifier,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('replies', ['user_id' => $user1->id, 'reply_id' => $r1->id]);
    }

    public function test_user_cannot_store_reply_to_reply_for_wrong_conversation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();
        $conversation2 = Conversation::factory()->byUser($user2)->create();

        $conversation->addUser($user1);

        $r1 = Talk::createReply($conversation2, $user1, ['message' => $this->faker->text()]);

        $response = $this->actingAs($user1)->put(route('talk.replies.store', ['conversation' => $conversation->identifier]), [
            'reply_message' => $this->faker->text(),
            'reply' => $r1->identifier,
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('replies', ['reply_id' => $r1->id]);
    }

    public function test_load_replies_tree()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->byUser($user1)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $r1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);
        $r2 = Talk::createReply($conversation, $user2, ['message' => $this->faker->text()]);
        $r3 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);
        $r2_1 = Talk::createReply($conversation, $user1, ['reply_message' => $this->faker->text(), 'reply' => $r2->identifier]);
        $r2_2 = Talk::createReply($conversation, $user2, ['reply_message' => $this->faker->text(), 'reply' => $r2->identifier]);

        $tree = Talk::repliesTree($conversation);

        $this->assertEquals($r1->user->id, $tree->get(0)->user->id);
        $this->assertEquals($r2_2->user->id, $tree->get(1)->replies->get(1)->user->id);
    }

    public function test_replies_are_deleted_recursively()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->byUser($user1)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $r2 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);
        $r2_1 = Talk::createReply($conversation, $user1, ['reply_message' => $this->faker->text(), 'reply' => $r2->identifier]);
        $r2_2 = Talk::createReply($conversation, $user2, ['reply_message' => $this->faker->text(), 'reply' => $r2_1->identifier]);
        $r2_2_1 = Talk::createReply($conversation, $user2, ['reply_message' => $this->faker->text(), 'reply' => $r2_2->identifier]);

        $r2->delete();
        
        $this->assertDatabaseMissing('replies', ['conversation_id' => $conversation->id]);
    }

    public function test_make_replies_anonymous()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user1)->create();

        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $r2 = Talk::createReply($conversation, $user2, ['message' => $this->faker->text()]);
        $r2_1 = Talk::createReply($conversation, $user2, ['reply_message' => $this->faker->text(), 'reply' => $r2->identifier]);
        $r2_2 = Talk::createReply($conversation, $user2, ['reply_message' => $this->faker->text(), 'reply' => $r2_1->identifier]);
        $r2_2_1 = Talk::createReply($conversation, $user2, ['reply_message' => $this->faker->text(), 'reply' => $r2_2->identifier]);

        $user2->anonymous_replies = true;
        $user2->delete();
        
        $this->assertDatabaseMissing('replies', ['user_id' => $user2->id]);
        $this->assertDatabaseHas('replies', ['conversation_id' => $conversation->id]);
    }

    public function test_show_reply_json()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user1)->create();

        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $r2 = Talk::createReply($conversation, $user2, ['message' => $this->faker->text()]);
        
        $response = $this->actingAs($user2)->get(route('talk.replies.show', ['conversation' => $conversation, 'reply' => $r2]));

        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route('talk.replies.show', ['conversation' => $conversation, 'reply' => $r2, 'raw' => 'false']));

        $response->assertStatus(200);
    }

    public function test_update_reply_json()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user1)->create();

        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $r2 = Talk::createReply($conversation, $user2, ['message' => $this->faker->text()]);

        $response = $this->actingAs($user2)->put(route('talk.replies.update', ['conversation' => $conversation, 'reply' => $r2]), []);
        $response->assertStatus(422);

        $response = $this->actingAs($user2)->put(route('talk.replies.update', ['conversation' => $conversation, 'reply' => $r2]), [
            'message' => 'New Text',
        ]);

        $response->assertStatus(200);
        $r2->refresh();

        $this->assertEquals('New Text', $r2->message);
    }
}