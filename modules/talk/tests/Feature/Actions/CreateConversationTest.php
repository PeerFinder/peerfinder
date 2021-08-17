<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Talk\Actions\CreateConversation;
use Talk\Models\Participant;
use Tests\TestCase;

/**
 * @group Talk
 */
class CreateConversationTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function test_create_conversation_by_action()
    {
        $user = User::factory()->create();

        $action = new CreateConversation();

        $conversation = $action->forUser($user, [
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

        $action = new CreateConversation();

        $this->expectException(ValidationException::class);

        $action->forUser($user, [
            'title' => $this->faker->text(),
        ]);
    }    
}