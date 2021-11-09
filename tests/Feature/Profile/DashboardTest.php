<?php

namespace Tests\Feature\Profile;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Matcher\Facades\Matcher;
use Matcher\Models\Appointment;
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
        $user2 = User::factory()->create();

        $pgs = Peergroup::factory(5)->byUser($user)->create();
        $pgs2 = Peergroup::factory(1)->byUser($user2)->create();

        Matcher::addMemberToGroup($pgs2[0], $user);

        $response = $this->actingAs($user)->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee($pgs[0]->getUrl());
        $response->assertSee($pgs2[0]->getUrl());
    }

    public function test_dashboard_shows_appointments()
    {
        $user = User::factory()->create();
        $pgs = Peergroup::factory(5)->byUser($user)->create();

        $ap1 = Appointment::factory()->forPeergroup($pgs[0])->create([
            'date' => Carbon::now()->addHour(),
        ]);

        $ap2 = Appointment::factory()->forPeergroup($pgs[1])->create([
            'date' => Carbon::now()->subHour(),
        ]);

        $ap3 = Appointment::factory()->forPeergroup($pgs[2])->create([
            'date' => Carbon::now()->addDay(),
        ]);        

        Matcher::addMemberToGroup($pgs[0], $user);
        Matcher::addMemberToGroup($pgs[1], $user);
        Matcher::addMemberToGroup($pgs[2], $user);

        $response = $this->actingAs($user)->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee($ap1->subject);
        $response->assertDontSee($ap2->subject);
        $response->assertSee($ap3->subject);
    }    
}
