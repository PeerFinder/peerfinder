<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Language;
use Matcher\Models\Peergroup;
use Tests\TestCase;

/**
 * @group Peergroup
 */
class LanguageTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_language_can_be_created()
    {
        $language = Language::factory()->create();
        $this->assertNotNull($language->code);
        $this->assertNotNull($language->title);
    }
}