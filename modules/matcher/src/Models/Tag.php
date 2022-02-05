<?php

namespace Matcher\Models;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    public static function getLocale()
    {
        return 'en';
    }

    public static function findFromString(string $name, string $type = null, string $locale = null)
    {
        $locale = $locale ?? static::getLocale();

        return static::query()->whereRaw('lower(' . static::query()->getGrammar()->wrap('name->' . $locale) . ') = ?', [mb_strtolower($name)])->first();
    }
}