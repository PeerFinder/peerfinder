<?php

namespace App\Providers;

use App\Helpers\Urler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class UrlerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('urler', function() {
            return new Urler();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
