<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Talk\Models\Participant;
use Tests\TestCase;

/**
 * @group Talk
 */
class ConversationTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_conversation_can_be_created_by_factory()
    {
        $conversation = Conversation::factory()->byUser()->create();
        $this->assertDatabaseCount('conversations', 1);
        $this->assertTrue(Str::isUuid($conversation->identifier));
    }

    public function test_user_can_participate_in_conversation()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->byUser()->create();

        $conversation->addUser($user);

        $this->assertEquals(1, $user->participated_conversations()->count());
        $this->assertEquals($user->email, $conversation->users()->first()->email);
    }

    public function test_user_can_create_conversation()
    {
        $user = User::factory()->create();

        $conversation = $user->owned_conversations()->create([
            'title' => $this->faker->text(),
        ]);

        $conversation->save();

        $this->assertEquals(1, $user->owned_conversations()->count());
        
        $conversation->users()->attach($user);
        $this->assertEquals(1, $user->participated_conversations()->count());
    }

    public function test_deleting_conversation()
    {
        $user = User::factory()->create();
        $conversation = $user->owned_conversations()->create([
            'title' => $this->faker->text(),
        ]);
        $conversation->save();

        $conversation->addUser($user);

        $conversation->delete();

        $this->assertDatabaseMissing('conversation_user', ['user_id' => $user->id]);
    }

    public function test_deleting_conversation_with_user()
    {
        $user = User::factory()->create();
        $conversation = $user->owned_conversations()->create([
            'title' => $this->faker->text(),
        ]);
        $conversation->save();

        $conversation->addUser($user);

        $uuid = $conversation->identifier;
        $user->delete();

        $this->assertDatabaseMissing('conversations', ['identifier' => $uuid]);
    }

    public function test_removing_user_from_conversations_on_deletion()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->byUser()->create();
        $conversation->addUser($user);
        $conversation->addUser($user2);

        $user_id = $user->id;
        $this->assertDatabaseHas('conversation_user', ['user_id' => $user_id]);

        $user->delete();
        $this->assertDatabaseMissing('conversation_user', ['user_id' => $user_id]);
    }

    public function test_user_can_render_conversations_list()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->byUser()->create();

        $conversation->addUser($user);

        $response = $this->actingAs($user)->get(route('talk.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_render_conversation()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('talk.show', ['conversation' => $conversation->identifier]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('talk.show', ['conversation' => "some-fake-id"]));
        $response->assertStatus(404);
    }

    public function test_user_can_only_see_conversations_as_participant_or_owner()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->byUser()->create();
        
        $conversation->addUser($user);

        $response = $this->actingAs($user)->get(route('talk.show', ['conversation' => $conversation->identifier]));
        $response->assertStatus(200);   

        $response = $this->actingAs($user2)->get(route('talk.show', ['conversation' => $conversation->identifier]));
        $response->assertStatus(403);

        $conversation->setOwner($user2);
        $this->assertTrue($conversation->isOwner($user2));
        $this->assertFalse($conversation->isOwner($conversation));

        $response = $this->actingAs($user2)->get(route('talk.show', ['conversation' => $conversation->identifier]));
        $response->assertStatus(200);
    }

    public function test_user_can_edit_conversation()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->byUser($user)->create();

        $conversation->addUser($user);

        $response = $this->actingAs($user)->get(route('talk.edit', ['conversation' => $conversation->identifier]));
        $response->assertStatus(200);
    }

    public function test_user_can_update_conversation_title()
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user)->create();

        $conversation->addUser($user);

        $new_title = $this->faker->text();

        $response = $this->actingAs($user)->put(route('talk.update', ['conversation' => $conversation->identifier]), [
            'title' => $new_title,
        ]);

        $conversation->refresh();

        $response->assertSessionHasNoErrors();
        $this->assertEquals($new_title, $conversation->title);
    }

    public function test_user_can_update_conversation_users()
    {
        $user = User::factory()->create();
        $users = User::factory(5)->create();

        $conversation = Conversation::factory()->byUser($user)->create();

        $conversation->addUser($user);

        $response = $this->actingAs($user)->put(route('talk.update', ['conversation' => $conversation->identifier]), [
            'users' => [
                $users[0]->username,
                $users[1]->username,
                $users[2]->username,
                'and-a-fake-username',
            ],
        ]);

        $conversation->refresh();

        $response->assertSessionHasNoErrors();
        $this->assertTrue($conversation->isParticipant($users[0]));
        $this->assertTrue($conversation->isParticipant($users[1]));
        $this->assertTrue($conversation->isParticipant($users[2]));
        $this->assertEquals(3, $conversation->users()->count());
    }    
}