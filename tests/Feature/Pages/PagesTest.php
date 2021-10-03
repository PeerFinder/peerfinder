<?php

namespace Tests\Feature\Pages;

use App\Helpers\Facades\Urler;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group pages
 */
class PagesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_load_content_page()
    {
        $page = Page::factory()->create();

        $response = $this->get(route('page.show', ['language' => 'de', 'slug' => $page->slug]));
        $response->assertStatus(200);
    }

    public function test_user_unknown_language_404()
    {
        $page = Page::factory()->create();
        $response = $this->get(route('page.show', ['language' => 'xy', 'slug' => $page->slug]));
        $response->assertStatus(404);
    }    
}