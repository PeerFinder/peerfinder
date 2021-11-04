<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Facades\EasyDate;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EasyDateTest extends TestCase
{
    public function test_conversion_to_utc()
    {
        $user = User::factory()->create([
            'timezone' => 'Europe/Berlin',
        ]);

        $this->be($user);

        $user_time = Carbon::parse("2021-06-01 15:00", $user->timezone);

        $utc_time = EasyDate::toUTC($user_time);

        $this->assertEquals("13:00", $utc_time->format('H:i'));
    }

    public function test_conversion_from_utc()
    {
        $user = User::factory()->create([
            'timezone' => 'Europe/Berlin',
        ]);

        $this->be($user);

        $database_time = Carbon::parse("2021-06-01 17:00");
        
        $user_time = EasyDate::fromUTC($database_time);

        $this->assertEquals("19:00", $user_time->format('H:i'));
    }

    public function test_conversion_from_utc_without_tz()
    {
        $database_time = Carbon::parse("2021-06-01 17:00");

        $user_time = EasyDate::fromUTC($database_time);

        $this->assertEquals("17:00", $user_time->format('H:i'));
    }
}
