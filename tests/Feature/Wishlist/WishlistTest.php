<?php

namespace Tests\Feature\Profile;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group wishlist
 */
class Wishlist extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_wishlist_not_available_for_guests()
    {
        $response = $this->get(route('support.wishlist.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_wishlist_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('support.wishlist.create'));
        $response->assertStatus(200);
    }

    public function test_wishlist_entry_cannot_be_stored_if_empty()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('support.wishlist.store'), [
            'body' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }    

    public function test_wishlist_entry_can_be_stored()
    {
        $user = User::factory()->create();

        $text = $this->faker->text();
        $context = $this->faker->text();

        $response = $this->actingAs($user)->put(route('support.wishlist.store'), [
            'body' => $text,
            'context' => $context,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('wishlist_entries', [
            'user_id' => $user->id,
            'body' => $text,
            'context' => $context,
        ]);
    }
}