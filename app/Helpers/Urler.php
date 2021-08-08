<?php

namespace App\Helpers;

class Urler
{
    public function fullUrl($url)
    {
        if (preg_match('%^(?:(?:https?|ftp):\/\/)%', $url) === 0) {
            $url = "http://" . $url;
        }
        
        return $url;
    }

    /**
     * Based on urlregex.com
     */
    public function validate($url)
    {
        return (preg_match('%^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url) === 1);
    }
}
