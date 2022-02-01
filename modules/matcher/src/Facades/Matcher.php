<?php

namespace Matcher\Facades;

use Illuminate\Support\Facades\Facade;

class Matcher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Matcher';
    }
}