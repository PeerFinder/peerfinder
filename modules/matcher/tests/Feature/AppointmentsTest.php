<?php

namespace Matcher\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Matcher\Facades\Matcher;
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

    public function test_group_owner_can_view_appointments()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $response = $this->actingAs($user)->get(route('matcher.appointments.index', ['pg' => $pg->groupname]));
        $response->assertStatus(200);

        $this->assertEquals($pg->id, $a->peergroup()->first()->id);
    }

    public function test_non_member_cannot_view_appointments()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        Appointment::factory()->forPeergroup($pg)->create();

        $response = $this->actingAs($user2)->get(route('matcher.appointments.index', ['pg' => $pg->groupname]));
        $response->assertStatus(403);
    }

    public function test_member_can_view_appointments()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        Appointment::factory()->forPeergroup($pg)->create();

        Matcher::addMemberToGroup($pg, $user2);

        $response = $this->actingAs($user2)->get(route('matcher.appointments.index', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
    }

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

        $date = Carbon::now($user->timezone);
        $end_date = $date->clone();
        $end_date->addHour();

        $data = [
            'subject' => $this->faker->realText(50),
            'details' => $this->faker->realText(50),
            'location' => $this->faker->city(),
            'date' => $date->format('d-m-Y'),
            'time' => $date->format('H:i'),
            'end_date' => $end_date->format('d-m-Y'),
            'end_time' => $end_date->format('H:i'),
        ];

        $response = $this->actingAs($user)->put(route('matcher.appointments.store', ['pg' => $pg->groupname]), $data);
        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);

        $date = $date->setTimezone('UTC')->second(0)->toDateTime();

        $this->assertDatabaseHas('appointments', ['peergroup_id' => $pg->id, 'subject' => $data['subject'], 'date' => $date]);
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

        $date = Carbon::now($user->timezone);
        $end_date = $date->clone();
        $end_date->addHour();

        $data = [
            'subject' => $this->faker->realText(50),
            'details' => $this->faker->realText(50),
            'location' => $this->faker->city(),
            'date' => $date->format('d-m-Y'),
            'time' => $date->format('H:i'),
            'end_date' => $end_date->format('d-m-Y'),
            'end_time' => $end_date->format('H:i'),
        ];

        $response = $this->actingAs($user)->put(route(
            'matcher.appointments.edit',
            ['pg' => $pg->groupname, 'appointment' => $a->identifier]
        ), $data);

        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);

        $date = $date->setTimezone('UTC')->second(0)->toDateTime();

        $this->assertDatabaseHas('appointments', ['peergroup_id' => $pg->id, 'subject' => $data['subject'], 'date' => $date]);
    }

    public function test_appointment_end_date_must_be_after_begin()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $date = Carbon::now($user->timezone);
        $end_date = $date->clone();

        $data = [
            'subject' => $this->faker->realText(50),
            'details' => $this->faker->realText(50),
            'location' => $this->faker->city(),
            'date' => $date->format('d-m-Y'),
            'time' => $date->format('H:i'),
            'end_date' => $end_date->format('d-m-Y'),
            'end_time' => $end_date->format('H:i'),
        ];

        $response = $this->actingAs($user)->put(route(
            'matcher.appointments.edit',
            ['pg' => $pg->groupname, 'appointment' => $a->identifier]
        ), $data);

        $response->assertSessionHasErrors();
    }

    public function test_group_owner_can_destroy_appointment()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $response = $this->actingAs($user)->delete(route(
            'matcher.appointments.destroy',
            ['pg' => $pg->groupname, 'appointment' => $a->identifier]
        ));

        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);

        $this->assertDatabaseMissing('appointments', ['peergroup_id' => $pg->id, 'subject' => $a->subject]);
    }

    public function test_appointments_are_deleted_with_peergroup()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $this->assertDatabaseHas('appointments', ['peergroup_id' => $pg->id]);

        $pg->delete();

        $this->assertDatabaseMissing('appointments', ['peergroup_id' => $pg->id]);
    }

    public function test_appointment_is_in_past()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $dateTime = Carbon::yesterday();

        $a->date = $dateTime;
        $a->save();

        $this->assertTrue($a->isInPast());

        $dateTime = Carbon::now()->subMinutes(30);

        $a->date = $dateTime;
        $a->save();

        $this->assertFalse($a->isInPast());

        $dateTime = Carbon::now()->addHour();

        $a->date = $dateTime;
        $a->save();

        $this->assertFalse($a->isInPast());
    }

    public function test_appointment_is_now()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $dateTime = Carbon::now();

        $a->date = $dateTime;
        $a->save();

        $this->assertTrue($a->isNow());

        $dateTime = Carbon::now()->addHour();

        $a->date = $dateTime;
        $a->save();

        $this->assertFalse($a->isNow());
    }

    public function test_download_appointment_ical()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();
        $a = Appointment::factory()->forPeergroup($pg)->create();

        $response = $this->actingAs($user)->get(route('matcher.appointments.download', ['pg' => $pg->groupname, 'appointment' => $a->identifier]));
        $response->assertStatus(200);

        $response->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        $response->assertSee($a->identifier);
    }

    public function test_appointment_inherits_peergroups_location()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $pg->inherit_location = true;
        $pg->virtual = false;
        $pg->save();

        $response = $this->actingAs($user)->get(route('matcher.appointments.create', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
        $response->assertSee($pg->location);
    }

    public function test_appointment_inherits_peergroups_meeting_link()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $pg->inherit_location = true;
        $pg->virtual = true;
        $pg->save();

        $response = $this->actingAs($user)->get(route('matcher.appointments.create', ['pg' => $pg->groupname]));
        $response->assertStatus(200);
        $response->assertSee($pg->meeting_link);
    }    
}
