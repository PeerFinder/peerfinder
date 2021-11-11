<?php

namespace Talk\Tests;

use App\Models\User;
use App\Notifications\UserHasUnreadReplies;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Talk\Events\UnreadReply;
use Talk\Facades\Talk;
use Talk\Models\Receipt;
use Tests\TestCase;

/**
 * @group Talk
 */
class ReceiptsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_api_gets_existing_receipts()
    {
        Event::fake(UnreadReply::class);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();

        $conversation->addUser($user2);
        $conversation->addUser($user1);

        $r1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $rc1 = Receipt::whereReplyId($r1->id)->whereUserId($user2->id)->first();
        $rc1->created_at = now()->subMinutes(50);
        $rc1->save();

        $r2 = Talk::createReply($conversation, $user2, ['message' => $this->faker->text()]);

        $rc2 = Receipt::whereReplyId($r2->id)->whereUserId($user1->id)->first();
        $rc2->created_at = now()->subMinutes(10);
        $rc2->save();

        $receipts = Talk::getUnreadReceipts(30, 2);
        $this->assertEquals(1, $receipts->count());

        Talk::sendNotificationsForReceipts();

        Event::assertDispatched(UnreadReply::class);

        $receipts = Talk::getUnreadReceipts(30, 2);
        $this->assertEquals(0, $receipts->count());
    }

    public function test_api_notifies_about_existing_receipts_after_calling_url()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user2)->create();

        $conversation->addUser($user2);
        $conversation->addUser($user1);

        $r1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);

        $rc1 = Receipt::whereReplyId($r1->id)->whereUserId($user2->id)->first();
        $rc1->created_at = now()->subMinutes(50);
        $rc1->save();

        $response = $this->get(route('talk.api.process-receipts'));

        $response->assertSee('OK');
    }
}