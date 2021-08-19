<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Talk\Actions\CreateConversation;
use Talk\Facades\Talk;
use Talk\Models\Participant;
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
        $user = User::factory()->create();

        $conversation = Talk::createConversationForUser($user, [
            'title' => $this->faker->text(),
            'body' => $this->faker->text(),
        ]);

        $this->assertEquals(1, $user->participated_conversations()->count());
        $this->assertEquals($user->email, $conversation->users()->first()->email);
        $this->assertEquals($user->email, $conversation->conversationable->email);
    }

    public function test_create_conversation_validates_input()
    {
        $user = User::factory()->create();

        $this->expectException(ValidationException::class);

        Talk::createConversationForUser($user, [
            'title' => $this->faker->text(),
        ]);
    }

    public function test_list_of_user_in_conversation()
    {
        $conversation = Conversation::factory()->byUser()->create();
        $users = User::factory(5)->create();

        $this->be($users->first());

        $conversation->addUser($users->get(0));

        $filtered_users = Talk::filterUsers($conversation);

        $this->assertCount(1, $filtered_users);

        $conversation->addUser($users->get(1));
        $conversation->addUser($users->get(2));

        $this->assertStringContainsString($users->first()->name, Talk::usersAsString($filtered_users));

        $filtered_users = Talk::filterUsers($conversation);

        $ids = array_map(fn($user) => $user->id, $filtered_users);

        $this->assertNotContains($users->first()->id, $ids);
    }
}