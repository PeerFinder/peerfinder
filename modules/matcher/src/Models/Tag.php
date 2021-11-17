<?php

namespace Matcher\Models;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    public static function getLocale()
    {
        return 'en';
    }
}

