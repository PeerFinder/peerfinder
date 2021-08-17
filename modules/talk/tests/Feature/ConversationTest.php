<?php

namespace Talk\Tests;

use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @group Talk
 */
class ConversationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_conversation_can_be_created_by_factory()
    {
        $conversation = Conversation::factory()->create();
        $this->assertDatabaseCount('conversations', 1);
        $this->assertTrue(Str::isUuid($conversation->identifier));
    }
}