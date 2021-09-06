<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
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
class MembershipTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;


    public function test_user_can_join_a_group_without_approval()
    {
        
    }

    public function test_user_cannot_join_a_group_with_approval()
    {
        
    }

    public function test_user_cannot_join_private_group()
    {
        
    }    

    public function test_user_cannot_join_full_group()
    {
        
    }

    public function test_user_cannot_join_completed_group()
    {
        
    }
}