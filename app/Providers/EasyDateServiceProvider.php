<?php

namespace App\Providers;

use App\Helpers\EasyDate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class EasyDateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('easydate', function() {
            return new EasyDate();
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
