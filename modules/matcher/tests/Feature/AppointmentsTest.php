<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Matcher\Models\Appointment;
use Tests\TestCase;

/**
 * @group Peergroup
 */
class AppointmentsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_group_owner_can_create_appointments()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $response = $this->actingAs($user)->get(route('matcher.appointments.create', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
    }
}