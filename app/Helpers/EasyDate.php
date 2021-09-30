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

    public function toUTC($dateTime)
    {
        $dateTime = Carbon::parse($dateTime, $this->timezone);
        $dateTime->setTimezone('UTC');
        return $dateTime;
    }

    public function fromUTC($dateTime)
    {
        $dateTime->setTimezone($this->timezone);
        return $dateTime;
    }

    public function joinAndConvertToUTC($date, $time)
    {
        $date = Carbon::parse($date, $this->timezone);

        $time = Carbon::parse($time, $this->timezone);
        
        $date->setTime($time->hour, $time->minute, 0);

        $date->setTimezone('UTC');

        return $date;
    }
}