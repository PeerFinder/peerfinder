<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_model_generates_username()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->username);
    }

    public function test_user_model_generates_unique_username()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->assertNotEquals($user1->username, $user2->username);
    }    
}
