<?php

namespace App\Helpers;

class Urler
{
    private $socialMediaPlatforms = [
        'twitter' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?twitter\.com\/([A-z0-9_]+)\/?(.*)$',
                '^([A-z0-9_]+)?$',
            ],
            'fullurl' => 'https://twitter.com/%s',
        ],
        'facebook' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?facebook\.com\/([A-z0-9_\-\.]+)\/?(.*)$',
                '^([A-z0-9_\-\.]+)?$',
            ],
            'fullurl' => 'https://facebook.com/%s',
        ],
        'linkedin' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?linkedin\.com\/in\/([A-z0-9_\-]+)\/?(.*)$',
                '^([A-z0-9_\-]+)?$',
            ],
            'fullurl' => 'https://www.linkedin.com/in/%s',
        ],
        'xing' => [
            'regex' => [
                '^(?:http(?:s)?:\/\/)?(?:[\w]+\.)?xing\.com\/profile\/([A-z0-9_\-\.]+)\/?(.*)$',
                '^([A-z0-9_\-\.]+)?$',
            ],
            'fullurl' => 'https://www.xing.com/profile/%s',
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
     * Based on urlregex.com
     */
    public function validate($url)
    {
        return (preg_match('%^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url) === 1);
    }

    public function sanitizeSocialMediaProfileUrl($socialMediaPlatform, $socialMediaProfileUrl)
    {
        $template = $this->socialMediaPlatforms[$socialMediaPlatform];

        $regex_list = $template['regex'];
        $fullurl = $template['fullurl'];

        foreach ($regex_list as $regex) {
            if (preg_match('/' . $regex . '/', $socialMediaProfileUrl, $matches)) {
                return sprintf($fullurl, $matches[1]);
            }
        }

        return sprintf($fullurl, $socialMediaProfileUrl);
    }
}
