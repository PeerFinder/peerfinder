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

    public function test_group_owner_can_store_appointments()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $data = [
            'subject' => $this->faker->realText(50),
            'details' => $this->faker->realText(50),
            'location' => $this->faker->city(),
            'date' => $this->faker->date(),
            'time' => $this->faker->time('H:i'),
        ];

        $response = $this->actingAs($user)->put(route('matcher.appointments.store', ['pg' => $pg->groupname]), $data);
        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('appointments', ['peergroup_id' => $pg->id, 'subject' => $data['subject']]);
    }

    public function test_group_owner_can_show_appointment()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $response = $this->actingAs($user)->get(route('matcher.appointments.show', ['pg' => $pg->groupname, 'appointment' => $a->identifier]));
        $response->assertStatus(200);
    }

    public function test_group_owner_can_edit_appointment()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $response = $this->actingAs($user)->get(route('matcher.appointments.edit', ['pg' => $pg->groupname, 'appointment' => $a->identifier]));
        $response->assertStatus(200);
    }

    public function test_group_owner_can_update_appointment()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $data = [
            'subject' => $this->faker->realText(50),
            'details' => $this->faker->realText(50),
            'location' => $this->faker->city(),
            'date' => $this->faker->date(),
            'time' => $this->faker->time('H:i'),
        ];

        $response = $this->actingAs($user)->put(route('matcher.appointments.edit', 
                ['pg' => $pg->groupname, 'appointment' => $a->identifier]), $data);
        
        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);
        
        $this->assertDatabaseHas('appointments', ['peergroup_id' => $pg->id, 'subject' => $data['subject']]);
    }

    public function test_group_owner_can_destroy_appointment()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $response = $this->actingAs($user)->delete(route('matcher.appointments.destroy', 
                ['pg' => $pg->groupname, 'appointment' => $a->identifier]));
        
        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);
        
        $this->assertDatabaseMissing('appointments', ['peergroup_id' => $pg->id, 'subject' => $a->subject]);
    }    
}