<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Facades\EasyDate;
use App\Models\User;
use Tests\TestCase;

class EasyDateTest extends TestCase
{
    public function test_conversion_to_utc()
    {
        $user = User::factory()->create([
            'timezone' => 'Europe/Berlin',
        ]);

        $this->be($user);

        $user_time = "15:00";

        $utc_time = EasyDate::toUTC($user_time);

        $this->assertEquals("13:00", $utc_time);
    }

    public function test_conversion_from_utc()
    {
        $user = User::factory()->create([
            'timezone' => 'Europe/Berlin',
        ]);

        $this->be($user);

        $database_time = "17:00";
        
        $user_time = EasyDate::fromUTC($database_time);

        $this->assertEquals("19:00", $user_time);
    }

    public function test_conversion_to_utc_without_tz()
    {
        $user_time = "15:00";

        $utc_time = EasyDate::toUTC($user_time);

        $this->assertEquals("15:00", $utc_time);
    }    
}
