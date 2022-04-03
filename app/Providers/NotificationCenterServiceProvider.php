<?php

namespace App\Providers;

use App\Helpers\NotificationCenter;
use Illuminate\Support\ServiceProvider;

class NotificationCenterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('notification_center', function() {
            return new NotificationCenter();
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
