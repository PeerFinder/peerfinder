<?php

namespace Talk\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Talk\Models\Conversation;
use Illuminate\Support\Str;
use Matcher\Models\Peergroup;
use Talk\Facades\Talk;
use Talk\Models\Receipt;
use Tests\TestCase;

/**
 * @group Talk
 */
class ReceiptsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_api_can_process_existing_receipts()
    {
        
    }
}