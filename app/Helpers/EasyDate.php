<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Carbon;

class EasyDate
{
    private $user = null;
    private $timezone = 'UTC';

    public function __construct()
    {
        $this->user = auth()->user();

        if ($this->user && $this->user->timezone) {
            $this->timezone = $this->user->timezone;
        }
    }

    public function toUTCTime($time)
    {
        $time = Carbon::parse($time, $this->timezone);

        $time->setTimezone('UTC');

        return $time->format('H:i');
    }

    public function fromUTCTime($time)
    {
        $time = Carbon::parse($time);

        $time->setTimezone($this->timezone);

        return $time->format('H:i');
    }

    public function joinDateTime($date, $time, $user_tz = false)
    {
        $time = Carbon::parse($time);

        $date->setTime($time->hour, $time->minute, 0);

        if ($user_tz) {
            $date->setTimezone($this->timezone);
        }

        return $date;
    }
}