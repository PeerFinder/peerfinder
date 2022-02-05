<?php

namespace App\Providers;

use App\Helpers\Pages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class PagesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('pages', function() {
            return new Pages();
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
