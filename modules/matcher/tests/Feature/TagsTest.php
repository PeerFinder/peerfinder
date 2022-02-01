<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Facades\Matcher;
use Matcher\Models\Language;

/**
 * @group Peergroup
 */
class TagsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_owner_can_store_group_tags()
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
            'tags' => [
                'Tag 1',
                'tag2',
                'tag 3'
            ]
        ];

        $response = $this->actingAs($user)->put(route('matcher.create'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $pg = Peergroup::whereTitle($data['title'])->first();

        $this->assertEquals(3, $pg->tags->count());
    }

    public function test_owner_can_edit_group_tags()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $pg->syncTags(['bla1', 'bla2']);

        $response = $this->actingAs($user)->get(route('matcher.edit', ['pg' => $pg->groupname]));

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);

        $response->assertSee('bla1');
        $response->assertSee('bla2');
    }

    public function test_owner_can_update_group_tags()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

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
            'tags' => [
                'Tag 1',
                'tag2',
                'tag 3'
            ]
        ];

        $response = $this->actingAs($user)->put(route('matcher.update', ['pg' => $pg->groupname]), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $pg = Peergroup::whereTitle($data['title'])->first();

        $this->assertEquals(3, $pg->tags->count());
    }

    public function test_owner_can_remove_group_tags()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $pg->syncTags(['a', 'b', 'c']);
        $pg->save();

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
            'languages' => [$language->code]
        ];

        $response = $this->actingAs($user)->put(route('matcher.update', ['pg' => $pg->groupname]), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $pg = Peergroup::whereTitle($data['title'])->first();

        $this->assertEquals(0, $pg->tags->count());
    }

    public function test_tags_are_not_case_sensitive()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $pg->syncTags(['a', 'b', 'c']);
        $pg->save();

        $res = Peergroup::withAnyTags(['A'])->first();

        $this->assertEquals($pg->id, $res->id);
    }
}