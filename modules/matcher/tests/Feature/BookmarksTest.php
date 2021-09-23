<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function test_group_owner_can_create_bookmarks()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $data = [
            'url[0]' => $this->faker->url(),
            'title[0]' => $this->faker->text(100),
            'url[1]' => $this->faker->url(),
            'title[1]' => $this->faker->text(100),
            'url[2]' => $this->faker->url(),
            'title[2]' => $this->faker->text(100),                        
        ];

        $response = $this->actingAs($user)->put(route('matcher.bookmarks.update'), $data);

        $response->assertSessionDoesntHaveErrors();
    }
}