<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Matcher\Models\Peergroup;
use Matcher\Models\Bookmark;
use Tests\TestCase;

/**
 * @group Peergroup
 */
class BookmarkTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_group_owner_can_edit_bookmarks()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('matcher.bookmarks.edit', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
    }

    public function test_group_owner_can_edit_existing_bookmarks()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $bookmarks = Bookmark::factory(5)->forPeergroup($pg)->create();

        $response = $this->actingAs($user)->get(route('matcher.bookmarks.edit', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
    }

    public function test_group_owner_can_edit_existing_bookmarks_with_errors()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        
        $errors = new ViewErrorBag();

        $errors->add('default', new MessageBag());

        session([
            '_old_input._token' => 'something',
            '_old_input.url' => ['a', 'b'],
            '_old_input.title' => ['a', 'b', 'c'],
            'errors' => $errors,
        ]);

        $response = $this->actingAs($user)->get(route('matcher.bookmarks.edit', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
    }

    public function test_group_owner_cannot_update_bookmarks_with_invalid_data()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $data = [
            'url' => [
                $this->faker->url(),
                $this->faker->text(),
                $this->faker->url(),
            ]
        ];

        $response = $this->actingAs($user)->put(route('matcher.bookmarks.update', ['pg' => $pg->groupname]), $data);
        
        $response->assertSessionHasErrors();
    }

    public function test_group_owner_can_update_bookmarks()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $data = [
            'url' => [
                $this->faker->url(),
                $this->faker->url(),
                $this->faker->url(),
            ],
            'title' => [
                $this->faker->text(100),
                $this->faker->text(100),
                $this->faker->text(100),
            ],
        ];

        $response = $this->actingAs($user)->put(route('matcher.bookmarks.update', ['pg' => $pg->groupname]), $data);
        
        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('bookmarks', ['peergroup_id' => $pg->id]);
    }

    public function test_bookmarks_are_deleted_with_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $bookmarks = Bookmark::factory(5)->forPeergroup($pg)->create();

        $this->assertDatabaseHas('bookmarks', ['peergroup_id' => $pg->id]);

        $pg->delete();

        $this->assertDatabaseMissing('bookmarks', ['peergroup_id' => $pg->id]);
    }

    public function test_group_owner_can_delete_all_bookmarks()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $data = [];

        $response = $this->actingAs($user)->put(route('matcher.bookmarks.update', ['pg' => $pg->groupname]), $data);
        
        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseMissing('bookmarks', ['peergroup_id' => $pg->id]);
    }
}