<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class Pages extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pages';
    }
}
