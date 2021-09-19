<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;
use Matcher\Events\PeergroupCreated;
use Matcher\Events\PeergroupDeleted;

/**
 * @group Peergroup
 */
class PeergroupTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_groupname_gets_automatically_generated()
    {
        $pg = Peergroup::factory()->byUser()->create();
        $this->assertNotNull($pg->groupname);
    }

    public function test_user_can_create_new_peergroup()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('matcher.create'));
        $response->assertStatus(200);
    }

    public function test_owner_can_store_peergroup()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();

        $data = [
            'title' => $this->faker->realText(50),
            'description' => $this->faker->text(),
            'limit' => $this->faker->numberBetween(2, config('matcher.max_limit')),
            'begin' => $this->faker->date(),
            'virtual' => $this->faker->boolean(),
            'private' => $this->faker->boolean(),
            'with_approval' => $this->faker->boolean(),
            'location' => $this->faker->city(),
            'meeting_link' => $this->faker->url(),
            'languages' => [$language->code],
        ];

        $response = $this->actingAs($user)->put(route('matcher.create'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        # Unset because languages are a part of language_peergroup table, not peergroups
        unset($data['languages']);

        $this->assertDatabaseHas('peergroups', $data);
    }


    public function test_event_is_triggered_when_group_created()
    {
        Event::fake(PeergroupCreated::class);

        $user = User::factory()->create();

        Peergroup::factory()->byUser($user)->create();

        Event::assertDispatched(PeergroupCreated::class);
    }

    public function test_event_is_triggered_when_group_deleted()
    {
        Event::fake(PeergroupDeleted::class);

        $user = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user)->create();

        $pg->delete();

        Event::assertDispatched(PeergroupDeleted::class);
    }

    public function test_user_can_show_public_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('matcher.show', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
        $response->assertSee($pg->title);
    }

    public function test_user_cannot_show_private_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser()->create([
            'private' => true,
        ]);

        $response = $this->actingAs($user)->get(route('matcher.show', ['pg' => $pg->groupname]));

        $response->assertStatus(403);
    }

    public function test_owner_can_show_private_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create([
            'private' => true,
        ]);

        $response = $this->actingAs($user)->get(route('matcher.show', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
    }

    public function test_member_can_show_private_peergroup()
    {
        $user = User::factory()->create();
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        Matcher::addMemberToGroup($pg, $user1);

        $pg->private = true;
        $pg->save();

        $response = $this->actingAs($user1)->get(route('matcher.show', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
    }    

    public function test_owner_can_edit_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('matcher.edit', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
        $response->assertSee($pg->title);
    }

    public function test_not_owner_cannot_edit_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser()->create();

        $response = $this->actingAs($user)->get(route('matcher.edit', ['pg' => $pg->groupname]));

        $response->assertStatus(403);
    }

    public function test_owner_can_update_peergroup_without_errors()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $language = Language::factory()->create();

        $data = [
            'title' => $this->faker->realText(50),
            'description' => $this->faker->text(),
            'limit' => $this->faker->numberBetween(2, config('matcher.max_limit')),
            'begin' => $this->faker->date(),
            'virtual' => $this->faker->boolean(),
            'private' => $this->faker->boolean(),
            'with_approval' => $this->faker->boolean(),
            'location' => $this->faker->city(),
            'meeting_link' => $this->faker->url(),
            'languages' => [$language->code],
        ];

        $response = $this->actingAs($user)->put(route('matcher.update', ['pg' => $pg->groupname]), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        # Unset because languages are a part of language_peergroup table, not peergroups
        unset($data['languages']);

        $this->assertDatabaseHas('peergroups', $data);

        $this->assertDatabaseHas('language_peergroup', ['peergroup_id' => $pg->id, 'language_id' => $language->id]);
    }

    public function test_owner_cannot_update_peergroup_with_errors()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $data = [
            'title' => '',
            'description' => '',
            'limit' => 0,
            'begin' => 'no-date',
            'virtual' => $this->faker->text(),
            'private' => $this->faker->text(),
            'with_approval' => $this->faker->text(),
            'meeting_link' => $this->faker->text(),
            'languages' => ['bla', 'blu']
        ];

        $response = $this->actingAs($user)->put(route('matcher.update', ['pg' => $pg->groupname]), $data);

        $response->assertSessionHasErrors(array_keys($data));

        $data = [
            'title' => Str::random(400),
            'description' => Str::random(1000),
            'limit' => config('matcher.max_limit') * 2,
            'location' => Str::random(300),
            'meeting_link' => 'http://' . Str::random(300) . '.com',
        ];

        $response = $this->actingAs($user)->put(route('matcher.update', ['pg' => $pg->groupname]), $data);

        $response->assertSessionHasErrors(array_keys($data));
    }

    public function test_owner_can_update_languages()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $language1 = Language::factory()->create();
        $language2 = Language::factory()->create();

        $data = [
            'title' => $this->faker->realText(50),
            'description' => $this->faker->text(),
            'limit' => $this->faker->numberBetween(2, config('matcher.max_limit')),
            'begin' => $this->faker->date(),
            'virtual' => $this->faker->boolean(),
            'private' => $this->faker->boolean(),
            'with_approval' => $this->faker->boolean(),
            'languages' => [
                $language1->code,
                $language2->code,
            ]
        ];

        $response = $this->actingAs($user)->put(route('matcher.update', ['pg' => $pg->groupname]), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('language_peergroup', ['peergroup_id' => $pg->id, 'language_id' => $language1->id]);
    }

    public function test_peergroups_deleted_with_user()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $this->assertTrue($user->ownsPeergroups());

        $user->delete();
        $this->assertDatabaseMissing('peergroups', ['id' => $pg->id]);
    }

    public function test_user_cannot_complete_the_group()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser()->create();
        $response = $this->actingAs($user)->post(route('matcher.complete', ['pg' => $pg->groupname]));
        $response->assertStatus(403);
    }

    public function test_owner_can_complete_the_group()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->post(route('matcher.complete', ['pg' => $pg->groupname]), [
            'status' => '1',
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $pg->refresh();
        $this->assertFalse($pg->isOpen());
    }

    public function test_owner_can_uncomplete_the_group()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create([
            'open' => false,
        ]);

        $response = $this->actingAs($user)->post(route('matcher.complete', ['pg' => $pg->groupname]), [
            'status' => '0',
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $pg->refresh();
        $this->assertTrue($pg->isOpen());
    }

    public function test_owner_cannot_uncomplete_full_group()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create([
            'limit' => 2,
        ]);

        Matcher::addMemberToGroup($pg, $user1);
        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user1)->post(route('matcher.complete', ['pg' => $pg->groupname]), [
            'status' => '0',
        ]);

        $response->assertSessionHasErrors();
        $pg->refresh();
        $this->assertFalse($pg->isOpen());
    }

    public function test_owner_can_render_ownership_transfer_view()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user1);
        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user1)->get(route('matcher.editOwner', ['pg' => $pg->groupname]));

        $response->assertStatus(200);
    }    

    public function test_owner_cannot_transfer_the_group_ownership_to_non_member()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user1)->put(route('matcher.editOwner', ['pg' => $pg->groupname]), [
            'owner' => $user2->username,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('owner');
        $this->assertNotEquals($pg->user()->first()->id, $user2->id);
    }

    public function test_owner_cannot_transfer_the_group_ownership_to_non_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        $response = $this->actingAs($user1)->put(route('matcher.editOwner', ['pg' => $pg->groupname]), [
            'owner' => 'some-fake-username',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('owner');
        $this->assertNotEquals($pg->user()->first()->id, $user2->id);
    }

    public function test_owner_can_transfer_the_group_ownership_to_member()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user1);
        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user1)->put(route('matcher.editOwner', ['pg' => $pg->groupname]), [
            'owner' => $user2->username,
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect($pg->getUrl());
        $pg->refresh();
        $this->assertEquals($pg->user()->first()->id, $user2->id);
    }

    public function test_owner_gets_redirected_after_ownership_transfer()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();

        Matcher::addMemberToGroup($pg, $user2);

        $pg->private = true;
        $pg->save();

        $response = $this->actingAs($user1)->put(route('matcher.editOwner', ['pg' => $pg->groupname]), [
            'owner' => $user2->username,
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect(route('dashboard.index'));

        $response = $this->actingAs($user1)->get(route('matcher.show', ['pg' => $pg->groupname]));
        $response->assertStatus(403);
    }

    public function test_owner_can_delete_the_group()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $pg = Peergroup::factory()->byUser($user)->create([
            'limit' => 5,
        ]);

        $language = Language::factory()->create();

        $pg->languages()->attach($language);
        
        Matcher::addMemberToGroup($pg, $user);
        Matcher::addMemberToGroup($pg, $user2);
        Matcher::addMemberToGroup($pg, $user3);

        $this->assertDatabaseHas('memberships', ['peergroup_id' => $pg->id]);

        $this->assertTrue($pg->hasMoreMembersThanOwner());

        $response = $this->actingAs($user)->get(route('matcher.delete', ['pg' => $pg->groupname]));

        $response->assertSee('This group has members');

        $response = $this->actingAs($user)->delete(route('matcher.delete', ['pg' => $pg->groupname]));
        $response->assertSessionHasErrors('confirm_delete');

        $response = $this->actingAs($user)->delete(route('matcher.delete', ['pg' => $pg->groupname]), [
            'confirm_delete' => '1'
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseMissing('peergroups', ['id' => $pg->id]);
        $this->assertDatabaseMissing('language_peergroup', ['peergroup_id' => $pg->id]);
        $this->assertDatabaseMissing('memberships', ['peergroup_id' => $pg->id]);
    }
}