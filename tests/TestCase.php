<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (!file_exists('.env.testing')) {
            dd('Tests run only in testing environment! .env.testing is missing!');
        }
    }
}
