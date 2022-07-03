<?php

namespace GroupRequests\Facades;

use Illuminate\Support\Facades\Facade;

class GroupRequests extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GroupRequests';
    }
}