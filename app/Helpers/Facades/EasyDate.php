<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class EasyDate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'easydate';
    }
}
