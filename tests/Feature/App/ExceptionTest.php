<?php

namespace Tests\Feature\App;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group exception
 */
class ExceptionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_not_found_as_html_response()
    {
        $response = $this->get('/bla/bla/bla');

        $response->assertStatus(404);

        $response->assertSee('Not Found');
    }

    public function test_not_found_as_json_response()
    {
        $response = $this->getJson('/bla/bla/bla');

        $response->assertStatus(404);

        $response->assertJson(['message' => __('Not Found!')]);
    }
}