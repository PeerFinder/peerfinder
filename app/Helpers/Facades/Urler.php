<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class Urler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'urler';
    }
}
