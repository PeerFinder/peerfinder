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

    
}