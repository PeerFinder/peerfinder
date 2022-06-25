<?php

namespace Requests\Facades;

use Illuminate\Support\Facades\Facade;

class Requests extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Requests';
    }
}