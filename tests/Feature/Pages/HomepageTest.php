<?php

namespace Tests\Feature\Profile;

use App\Helpers\Facades\Urler;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

/**
 * @group pages
 */
class HomepageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_load_homepage()
    {
        $page = Page::factory()->create([
            'slug' => 'homepage',
        ]);

        $response = $this->get(route('homepage.show', ['language' => 'de']));
        $response->assertStatus(200);
    }

    public function test_user_homepage_unknown_language_404()
    {
        $page = Page::factory()->create([
            'slug' => 'homepage',
        ]);

        $response = $this->get(route('homepage.show', ['language' => 'de']));
        $response->assertStatus(200);

        $response = $this->get(route('homepage.show', ['language' => 'xy']));
        $response->assertStatus(404);
    }

    public function test_homepage_redirects_to_language()
    {
        $page = Page::factory()->create([
            'slug' => 'homepage',
        ]);

        $response = $this->get(route('index'));
        $response->assertStatus(302);
        $response->assertLocation(route('homepage.show', ['language' => 'en']));

        $response = $this->get(route('index'), ['HTTP_ACCEPT_LANGUAGE' => 'de']);
        $response->assertStatus(302);
        $response->assertLocation(route('homepage.show', ['language' => 'de']));
    }
}