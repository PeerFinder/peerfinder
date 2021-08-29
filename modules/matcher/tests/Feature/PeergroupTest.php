<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Illuminate\Support\Str;
use Matcher\Models\Language;

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

        unset($data['languages']);
        $this->assertDatabaseHas('peergroups', $data);
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
        $user->delete();
        $this->assertDatabaseMissing('peergroups', ['id' => $pg->id]);
    }
}