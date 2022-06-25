<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Facades\Urler;
use App\Models\User;
use Tests\TestCase;

class UrlerTest extends TestCase
{
    public function test_prefix_url()
    {
        $this->assertEquals('https://google.com', Urler::fullUrl('google.com'));
        $this->assertEquals('https://twitter.com/blabla', Urler::fullUrl('twitter.com/blabla'));
        $this->assertEquals('https://google.com', Urler::fullUrl('https://google.com'));
        $this->assertEquals('http://google.com', Urler::fullUrl('http://google.com'));
    }

    public function test_validate_url()
    {
        $this->assertTrue(Urler::validate('http://google.com'));
        $this->assertTrue(Urler::validate('https://google.com///'));
        $this->assertTrue(Urler::validate('http://äöp-xy.de'));
        $this->assertFalse(Urler::validate('http://google'));
        $this->assertFalse(Urler::validate('google.com'));
        $this->assertFalse(Urler::validate('google'));
        $this->assertFalse(Urler::validate('127.0.0.1'));
        $this->assertFalse(Urler::validate('http://localhost'));
        $this->assertFalse(Urler::validate(''));
    }

    public function test_social_profile_urls()
    {
        $this->assertEquals(
            'https://www.linkedin.com/in/your-username',
            Urler::sanitizeSocialMediaProfileUrl('linkedin', 'your-username')
        );

        $this->assertEquals(
            'https://www.linkedin.com/in/your-username',
            Urler::sanitizeSocialMediaProfileUrl('linkedin', 'https://www.linkedin.com/in/your-username')
        );

        $this->assertEquals(
            'https://www.linkedin.com/in/your-username',
            Urler::sanitizeSocialMediaProfileUrl('linkedin', 'www.linkedin.com/in/your-username/')
        );

        $this->assertEquals(
            'https://www.linkedin.com/in/your-username',
            Urler::sanitizeSocialMediaProfileUrl('linkedin', 'linkedin.com/in/your-username')
        );

        $this->assertEquals(
            'https://www.xing.com/profile/your-username',
            Urler::sanitizeSocialMediaProfileUrl('xing', 'your-username')
        );

        $this->assertEquals(
            'https://www.xing.com/profile/your-username',
            Urler::sanitizeSocialMediaProfileUrl('xing', 'https://www.xing.com/profile/your-username')
        );

        $this->assertEquals(
            'https://www.xing.com/profile/your-username',
            Urler::sanitizeSocialMediaProfileUrl('xing', 'www.xing.com/profile/your-username')
        );

        $this->assertEquals(
            'https://www.xing.com/profile/SomeUserBlahBlah',
            Urler::sanitizeSocialMediaProfileUrl('xing', 'https://www.xing.com/profile/SomeUserBlahBlah/cv?sc_o=mxb_p')
        );

        $this->assertEquals(
            'https://facebook.com/SomeUserBlahBlah',
            Urler::sanitizeSocialMediaProfileUrl('facebook', 'https://m.facebook.com/SomeUserBlahBlah?ref=bookmarks')
        );

        $this->assertEquals(
            'https://twitter.com/your-username',
            Urler::sanitizeSocialMediaProfileUrl('twitter', '@your-username')
        );
    }

    public function test_user_profile_url()
    {
        $user = User::factory()->create();
        $this->assertEquals(route('profile.user.show', ['user' => $user->username]), Urler::userProfileUrl($user));
    }
}
