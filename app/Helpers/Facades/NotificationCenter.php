<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class NotificationCenter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'notification_center';
    }
}
