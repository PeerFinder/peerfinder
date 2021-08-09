<?php

namespace Tests\Feature;

use App\Helpers\Facades\Urler;
use Tests\TestCase;

class UrlerTest extends TestCase
{
    public function test_prefix_url()
    {
        $this->assertEquals('http://google.com', Urler::fullUrl('google.com'));
        $this->assertEquals('http://twitter.com/blabla', Urler::fullUrl('twitter.com/blabla'));
        $this->assertEquals('https://google.com', Urler::fullUrl('https://google.com'));
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
}
