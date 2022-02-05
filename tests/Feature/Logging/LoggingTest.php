<?php

namespace Tests\Feature\Logging;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Matcher\Models\Peergroup;
use Tests\TestCase;
use Matcher\Facades\Matcher;
use Matcher\Models\Membership;
use Spatie\Activitylog\Models\Activity;

/**
 * @group logging
 */
class LoggingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_log_user_created()
    {
        $user = User::factory()->create();

        $lastActivity = Activity::all()->last();

        $this->assertEquals(User::class, $lastActivity->subject_type);
        $this->assertEquals($user->id, $lastActivity->subject_id);
    }

    public function test_log_peergroup_created()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $lastActivity = Activity::all()->last();

        $this->assertEquals(Peergroup::class, $lastActivity->subject_type);
        $this->assertEquals($pg->id, $lastActivity->subject_id);
    }

    public function test_log_membership_created()
    {
        $user = User::factory()->create();
        $pg = Peergroup::factory()->byUser($user)->create();

        $m1 = Matcher::addMemberToGroup($pg, $user);

        $lastActivity = Activity::all()->last();

        $this->assertEquals(Membership::class, $lastActivity->subject_type);
        $this->assertEquals($m1->id, $lastActivity->subject_id);
    }
}
