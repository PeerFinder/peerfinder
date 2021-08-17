<?php

namespace Talk;

use Illuminate\Support\ServiceProvider;

class TalkServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerResources();
    }

    public function register()
    {
        
    }

    public function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}