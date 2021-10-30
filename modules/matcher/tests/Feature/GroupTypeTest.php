<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\GroupType;
use Matcher\Models\Language;
use Matcher\Models\Peergroup;
use Tests\TestCase;

/**
 * @group Peergroup
 */
class GroupTypeTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_group_type_with_parents()
    {
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();
        $gt1 = GroupType::factory()->create();
        $gt1_1 = GroupType::factory()->withParent($gt1)->create();
        $gt1_2 = GroupType::factory()->withParent($gt1)->create();
        $gt1_1_1 = GroupType::factory()->withParent($gt1_1)->create();

        $pg->groupType()->associate($gt1_1_1);
        $pg->save();

        $pg->refresh();

        $types = $pg->groupType()->first()->getRecursiveTypes();

        $this->assertCount(3, $types);
        $this->assertEquals($gt1_1->id, $types[1]->id);
        $this->assertEquals(2, $gt1->groupTypes()->count());
    }

    public function test_owner_can_set_group_type_when_creating()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();
        $gt1 = GroupType::factory()->create();

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
            'group_type' => $gt1->id,
        ];

        $response = $this->actingAs($user)->put(route('matcher.create'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $pg = $user->peergroups()->first();

        $this->assertEquals($gt1->id, $pg->groupType()->first()->id);
    }

    public function test_owner_cannot_set_unknown_group_type_when_creating()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();
        $gt1 = GroupType::factory()->create();

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
            'group_type' => $gt1->id + 1,
        ];

        $response = $this->actingAs($user)->put(route('matcher.create'), $data);

        $response->assertSessionHasErrors();
    }    
}