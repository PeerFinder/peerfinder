<?php

namespace GroupRequests\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use GroupRequests\Models\GroupRequest;
use Matcher\Models\Language;
use Tests\TestCase;

/**
 * @group GroupRequests
 */
class GroupRequestsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_guest_cannot_render_group_requests()
    {
        $response = $this->get(route('group_requests.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_render_group_requests_list()
    {
        $user = User::factory()->create();
        $group_request = GroupRequest::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('group_requests.index'));
        $response->assertStatus(200);

        $response->assertSee($group_request->title);
    }

    public function test_user_can_create_group_request()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('group_requests.create'));

        $response->assertStatus(200);
    }

    public function test_user_can_store_group_request()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();

        $data = [
            'title' => $this->faker->realText(50),
            'description' => $this->faker->text(),
            'languages' => [$language->code],
        ];

        $response = $this->actingAs($user)->put(route('group_requests.create'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        unset($data['languages']);
        $this->assertDatabaseHas('group_requests', $data);

        $group_request = GroupRequest::first();

        $this->assertDatabaseHas('group_request_language', [
            'language_id' => $language->id,
            'group_request_id' => $group_request->id,
        ]);
    }

    public function test_user_cannot_store_group_request()
    {
        $user = User::factory()->create();

        $data = [
            'title' => $this->faker->realText(50),
            'description' => $this->faker->text(),
        ];

        $response = $this->actingAs($user)->put(route('group_requests.create'), $data);

        $response->assertSessionHasErrors();
    }

    public function test_user_can_update_group_request()
    {
        $user = User::factory()->create();
        $language = Language::factory()->create();
        $group_request = GroupRequest::factory()->byUser($user)->create();

        $data = [
            'title' => $this->faker->realText(50),
            'description' => $this->faker->text(),
            'languages' => [$language->code],
        ];

        $response = $this->actingAs($user)->put(route('group_requests.update', ['group_request' => $group_request->identifier]), $data);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        unset($data['languages']);
        $this->assertDatabaseHas('group_requests', $data);
    }
}
