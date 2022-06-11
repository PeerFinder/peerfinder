<?php

namespace App\Providers;

use App\Helpers\Infocards;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class InfocardsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('infocards', function() {
            return new Infocards();
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
