<?php

namespace Talk\Tests;

use App\Models\User;
use GroupRequests\Models\GroupRequest;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Matcher\Models\Peergroup;
use Talk\Facades\Talk;
use Talk\Models\Participant;
use Tests\TestCase;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

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

    public function test_user_can_be_removed_from_conversation()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->byUser()->create();

        $conversation->addUser($user);

        $this->assertTrue($conversation->isParticipant($user));

        $conversation->removeUser($user);

        $conversation->refresh();

        $this->assertFalse($conversation->isParticipant($user));
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

        $conversation->addUser($user);

        $response = $this->actingAs($user)->get(route('talk.show', ['conversation' => $conversation->identifier]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('talk.show', ['conversation' => "some-fake-id"]));
        $response->assertStatus(404);
    }

    public function test_user_can_only_see_conversations_as_participant()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->byUser($user2)->create();

        $conversation->addUser($user);

        $response = $this->actingAs($user)->get(route('talk.show', ['conversation' => $conversation->identifier]));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route('talk.show', ['conversation' => $conversation->identifier]));
        $response->assertStatus(403);
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
            'search_users' => [
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

    public function test_create_conversation_for_single_user()
    {
        $user = User::factory()->create();
        $user1 = User::factory()->create();

        $conversation = Conversation::factory()->byUser()->create();
        $conversation->addUser($user);

        $response = $this->actingAs($user)->get(route('talk.create.user', ['usernames' => $user1->username]));
        $response->assertStatus(200);
    }

    public function test_redirect_to_existent_conversation_for_single_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user3)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $response = $this->actingAs($user1)->get(route('talk.create.user', ['usernames' => $user2->username]));
        $response->assertStatus(200);
        
        $conversation->setOwner($user1);
        
        $this->assertFalse($conversation->isOwner($conversation)); // check for a different type
        $this->assertTrue($conversation->isOwner($user1));

        $response = $this->actingAs($user1)->get(route('talk.create.user', ['usernames' => $user2->username]));
        $response->assertStatus(302);
        $response->assertRedirect(route('talk.show', ['conversation' => $conversation->identifier]));

        $response = $this->actingAs($user2)->get(route('talk.create.user', ['usernames' => $user1->username]));        
        $response->assertStatus(302);
        $response->assertRedirect(route('talk.show', ['conversation' => $conversation->identifier]));
    }

    public function test_conversation_for_multiple_users_with_error()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user2)->get(route('talk.create.user', ['usernames' => implode(',', [$user1->username, 'bla-bla'])]));
        $response->assertStatus(404);
    }

    public function test_conversation_can_render_for_multiple_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $response = $this->actingAs($user1)->get(route('talk.create.user', ['usernames' => implode(',', [$user2->username, $user3->username])]));
        $response->assertStatus(200);
    }

    public function test_conversation_can_create_for_multiple_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $response = $this->actingAs($user1)->put(route('talk.store.user', ['usernames' => implode(',', [$user2->username, $user3->username])]), [
            'message' => $this->faker->text(),
        ]);

        $response->assertStatus(302);

        $this->assertEquals(1, $user1->owned_conversations()->count());
        $this->assertEquals(3, $user1->owned_conversations()->first()->users()->count());
        $this->assertEquals(1, $user2->participated_conversations()->count());
        $this->assertEquals(1, $user3->participated_conversations()->count());
    }

    public function test_not_redirect_to_conversation_with_more_participants()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user4)->create();

        $conversation->addUser($user1);
        $conversation->addUser($user2);
        $conversation->addUser($user3);

        $response = $this->actingAs($user1)->get(route('talk.create.user', ['usernames' => $user2->username]));
        $response->assertStatus(200);
    }

    public function test_new_conversation_if_not_owned()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $conversation = Conversation::factory()->byUser($user3)->create();
        $conversation->addUser($user1);
        $conversation->addUser($user2);

        $response = $this->actingAs($user1)->get(route('talk.create.user', ['usernames' => $user2->username]));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route('talk.create.user', ['usernames' => $user1->username]));
        $response->assertStatus(200);
    }

    public function test_no_conversation_with_self()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $response = $this->actingAs($user1)->get(route('talk.create.user', ['usernames' => $user1->username]));
        $response->assertStatus(302);
        $response->assertRedirect(route('talk.index'));
    }

    public function test_user_creates_conversation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)->put(route('talk.store.user', ['usernames' => $user1->username]), [
            'message' => $this->faker->text(),
        ]);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('talk.index'));

        $response = $this->actingAs($user1)->put(route('talk.store.user', ['usernames' => $user2->username]), [
            'message' => $this->faker->text(),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertEquals(1, $user1->owned_conversations()->count());
        $this->assertEquals(1, $user1->participated_conversations()->count());
        $this->assertEquals(1, $user2->participated_conversations()->count());
        $this->assertDatabaseHas('replies', ['user_id' => $user1->id]);
    }

    public function test_title_of_the_user_conversation_is_dynamic()
    {
        $user = User::factory()->create();
        $user1 = User::factory()->create();
        $conversation = Conversation::factory()->byUser($user)->create();

        $this->be($user);

        $conversation->addUser($user);
        $conversation->addUser($user1);

        $this->assertEquals($conversation->title, $conversation->getTitle());

        $conversation->title = '';
        $this->assertStringContainsString($user1->name, $conversation->getTitle());
    }

    public function test_title_of_the_peergroup_conversation_is_dynamic()
    {
        $user = User::factory()->create();
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $conversation = Conversation::factory()->byPeergroup($pg)->create();

        $this->assertEquals($pg->title, $conversation->getTitle());
    }

    public function test_select_users_for_conversation()
    {
        $user1 = User::factory()->create();

        $response = $this->actingAs($user1)->get(route('talk.select'));
        $response->assertStatus(200);
    }

    public function test_select_users_for_conversation_with_errors()
    {
        $user1 = User::factory()->create();
        
        $errors = new ViewErrorBag();
        $errors->add('default', new MessageBag());

        session([
            '_old_input._token' => 'something',
            '_old_input.search_users' => ['userA', 'userB'],
            'errors' => $errors,
        ]);

        $response = $this->actingAs($user1)->get(route('talk.select'));

        $response->assertStatus(200);
        $response->assertSee('userA');
        $response->assertSee('userB');
    }

    public function test_select_users_for_conversation_and_redirect()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        
        $response = $this->actingAs($user1)->post(route('talk.select'), [
            'search_users' => [
                'userA',
                'userB',
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $response = $this->actingAs($user1)->post(route('talk.select'), [
            'search_users' => [
                $user2->username,
                $user3->username,
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('talk.create.user', ['usernames' => $user2->username . ',' . $user3->username]));
    }

    public function test_user_cannot_join_closed_conversation()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->post(route('talk.join', ['conversation' => $conversation->identifier]));
        $response->assertStatus(403);
    }

    public function test_user_can_join_open_conversation()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $group_request = GroupRequest::factory()->byUser($user)->create();
        $conversation = $group_request->conversations()->first();

        $response = $this->actingAs($user2)->post(route('talk.join', ['conversation' => $conversation->identifier]));
        $response->assertStatus(302);

        $this->assertTrue($conversation->isParticipant($user));
        $this->assertTrue($conversation->isParticipant($user2));
    }

    public function test_user_can_leave_open_conversation()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $group_request = GroupRequest::factory()->byUser($user)->create();
        $conversation = $group_request->conversations()->first();

        $conversation->addUser($user2);

        $response = $this->actingAs($user2)->post(route('talk.leave', ['conversation' => $conversation->identifier]));
        $response->assertStatus(302);

        $this->assertTrue($conversation->isParticipant($user));
        $this->assertFalse($conversation->isParticipant($user2));
    }    
}
