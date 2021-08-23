<?php

namespace Talk\Facades;

use Illuminate\Support\Facades\Facade;

class Talk extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Talk';
    }
}