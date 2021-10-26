<?php

namespace App\Helpers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;

class Urler
{
    private $socialMediaPlatforms = [
        'twitter' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?twitter\.com\/([A-z0-9_]+)\/?(.*)$',
                '^([A-z0-9_]+)?$',
            ],
            'fullUrl' => 'https://twitter.com/%s',
        ],
        'facebook' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?facebook\.com\/([A-z0-9_\-\.]+)\/?(.*)$',
                '^([A-z0-9_\-\.]+)?$',
            ],
            'fullUrl' => 'https://facebook.com/%s',
        ],
        'linkedin' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?linkedin\.com\/in\/([A-z0-9_\-]+)\/?(.*)$',
                '^([A-z0-9_\-]+)?$',
            ],
            'fullUrl' => 'https://www.linkedin.com/in/%s',
        ],
        'xing' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?xing\.com\/profile\/([A-z0-9_\-\.]+)\/?(.*)$',
                '^([A-z0-9_\-\.]+)?$',
            ],
            'fullUrl' => 'https://www.xing.com/profile/%s',
        ],
    ];

    public function getSocialPlatforms() {
        return $this->socialMediaPlatforms;
    }

    public function fullUrl($url)
    {
        if (preg_match('%^(?:(?:https?|ftp):\/\/)%', $url) === 0) {
            $url = "http://" . $url;
        }

        return $url;
    }

    /**
     * @source urlregex.com
     */
    public function validate($url)
    {
        return (preg_match('%^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url) === 1);
    }

    public function sanitizeSocialMediaProfileUrl($socialMediaPlatform, $socialMediaProfileUrl)
    {
        $socialMediaProfileUrl = str_replace('@', '', $socialMediaProfileUrl);
        
        $template = $this->socialMediaPlatforms[$socialMediaPlatform];

        $regex_list = $template['regex'];

        $fullUrl = $template['fullUrl'];

        foreach ($regex_list as $regex) {
            if (preg_match('/' . $regex . '/', $socialMediaProfileUrl, $matches)) {
                return sprintf($fullUrl, $matches[1]);
            }
        }
        
        return sprintf($fullUrl, $socialMediaProfileUrl);
    }

    public function createUniqueSlug($modelInstance, $field, $length = 17)
    {
        $model = get_class($modelInstance);

        do {
            $slug = Str::random($length);
        } while ($model::where($field, $slug)->exists());

        $modelInstance->$field = $slug;
    }

    public function userProfileUrl($user)
    {
        return $user->profileUrl();
    }

    /**
     * @source: https://gist.github.com/Krato/1c8d71b8688f646a3c9df3501ce341f3 
     */
    function versioned_asset($path, $secure = null)
    {
        $timestamp = @filemtime(public_path($path)) ?: 0;

        return asset($path, $secure) . '?' . $timestamp;
    }
}
