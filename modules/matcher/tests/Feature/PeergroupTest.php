<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Tests\TestCase;

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

    public function test_user_can_show_public_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('matcher.show', ['pg' => $pg->groupname]));
        
        $response->assertStatus(200);
        $response->assertSee($pg->title);
    }
}