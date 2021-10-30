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

    public function test_group_type_()
    {
        $user1 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user1)->create();
        $gt1 = GroupType::factory()->create();
        $gt1_1 = GroupType::factory()->withParent($gt1)->create();
        $gt1_1_1 = GroupType::factory()->withParent($gt1_1)->create();

        $pg->groupType()->associate($gt1_1_1);
        $pg->save();

        $pg->refresh();

        $types = $pg->groupType()->first()->getRecursiveTypes();
    }
}