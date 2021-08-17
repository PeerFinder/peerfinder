<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
    use DatabaseMigrations;
    use WithFaker;

    public function test_conversation_can_be_created_by_factory()
    {
        $conversation = Conversation::factory()->create();
        $this->assertDatabaseCount('conversations', 1);
        $this->assertTrue(Str::isUuid($conversation->identifier));
    }

    public function test_user_can_participate_in_conversation()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $conversation->users()->attach($user);

        $this->assertEquals(1, $user->participated_conversations()->count());
        $this->assertEquals($user->email, $conversation->users()->first()->email);
    }

    public function test_user_can_create_conversation()
    {
        $user = User::factory()->create();

        $conversation = $user->owned_conversations()->create([
            'title' => $this->faker->text(),
            'body' => $this->faker->text(),
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
            'body' => $this->faker->text(),
        ]);
        $conversation->save();

        $conversation->users()->attach($user);

        $conversation->delete();

        $this->assertDatabaseCount('conversation_user', 0);
        $this->assertDatabaseCount('conversations', 0);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_deleting_conversation_with_user()
    {
        $user = User::factory()->create();
        $conversation = $user->owned_conversations()->create([
            'title' => $this->faker->text(),
            'body' => $this->faker->text(),
        ]);
        $conversation->save();

        $conversation->users()->attach($user);

        $uuid = $conversation->identifier;
        $user->delete();

        $this->assertDatabaseMissing('conversations', ['identifier' => $uuid]);
    }

    public function test_removing_user_from_conversations_on_deletion()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $conversation->users()->attach($user);
        $conversation->users()->attach($user2);

        $user->delete();

        $this->assertDatabaseCount('conversation_user', 1);
        $this->assertDatabaseCount('conversations', 1);
    }
}