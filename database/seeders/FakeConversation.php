<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Facades\Talk;
use Talk\Models\Conversation;

class FakeConversation extends Seeder
{
    use WithFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setUpFaker();

        $user1 = User::factory()->create();
        $user2 = User::whereEmail('twols@me.com')->first();
        $user3 = User::factory()->create();
        #$conversation = Conversation::whereIdentifier('2448ff5c-89d2-4df8-928a-f99ad2385ae0')->first(); 
        $conversation = Conversation::factory()->byUser($user1)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);
        $conversation->addUser($user3);

        $r1 = Talk::createReply($conversation, $user1, ['message' => $this->faker->text()]);
        $r2 = Talk::createReply($conversation, $user2, ['message' => $this->faker->text()]);

        $r2_1 = Talk::createReply($r2, $user1, ['message' => $this->faker->text()]);
        $r2_2 = Talk::createReply($r2, $user2, ['message' => $this->faker->text()]);

        $r2_2_1 = Talk::createReply($r2_2, $user1, ['message' => $this->faker->text()]);
        $r2_2_2 = Talk::createReply($r2_2, $user3, ['message' => $this->faker->text()]);
        $r2_2_3 = Talk::createReply($r2_2, $user2, ['message' => $this->faker->text()]);

        $r2_3 = Talk::createReply($r2, $user2, ['message' => $this->faker->text()]);

        $r3 = Talk::createReply($conversation, $user3, ['message' => $this->faker->text()]);

        $r3_1 = Talk::createReply($r3, $user1, ['message' => $this->faker->text()]);

        for ($i=0; $i < 10; $i++) { 
            $r = Talk::createReply($r3, $user1, ['message' => $this->faker->text()]);
            $r = Talk::createReply($r, $user2, ['message' => $this->faker->text()]);
            $r = Talk::createReply($r, $user3, ['message' => $this->faker->text()]);
        }


        dump($conversation->identifier);
    }
}
