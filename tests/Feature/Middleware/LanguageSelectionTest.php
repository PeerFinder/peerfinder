<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LanguageSelectionTest extends TestCase
{
    public function test_locale_set_from_http_header()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => $user->username]), [
            'HTTP_ACCEPT_LANGUAGE' => 'de',
        ]);

        $response->assertStatus(200);
        $response->assertSee('<html lang="de">', false);

        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => $user->username]), [
            'HTTP_ACCEPT_LANGUAGE' => 'xy',
        ]);

        $response->assertStatus(200);
        $response->assertSee('<html lang="en">', false);
    }

    public function test_locale_set_from_user_locale()
    {
        $user = User::factory()->create([
            'locale' => 'de',
        ]);

        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => $user->username]), [
            'HTTP_ACCEPT_LANGUAGE' => 'en',
        ]);

        $response->assertStatus(200);
        $response->assertSee('<html lang="de">', false);
    }

    public function test_locale_set_from_user_bad_locale()
    {
        $user = User::factory()->create([
            'locale' => 'xy',
        ]);

        $response = $this->actingAs($user)->get(route('profile.user.show', ['user' => $user->username]), [
            'HTTP_ACCEPT_LANGUAGE' => 'en',
        ]);

        $response->assertStatus(200);
        $response->assertSee('<html lang="en">', false);
    }    
}
