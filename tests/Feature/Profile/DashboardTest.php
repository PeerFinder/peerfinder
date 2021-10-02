<?php

namespace Tests\Feature\Profile;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Tests\TestCase;

/**
 * @group profile
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_dashboard_not_available_for_guests()
    {
        $response = $this->get(route('dashboard.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_available_for_users()
    {
        $user = User::factory()->create();
        $pgs = Peergroup::factory(5)->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee($pgs[0]->getUrl());
    }
}
