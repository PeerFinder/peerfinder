<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth', 'verified'])
                ->namespace('App\Http\Controllers\Account')
                ->group(base_path('routes/verified/account.php'));

            Route::middleware(['web', 'auth', 'verified'])
                ->namespace('App\Http\Controllers\Dashboard')
                ->group(base_path('routes/verified/dashboard.php'));

            Route::middleware(['web', 'auth', 'verified'])
                ->namespace('App\Http\Controllers\Notifications')
                ->group(base_path('routes/verified/notifications.php'));
            
            Route::middleware(['web', 'auth', 'verified'])
                ->namespace('App\Http\Controllers\Profile')
                ->group(base_path('routes/verified/profile.php'));

            Route::middleware(['web', 'auth'])
                ->namespace('App\Http\Controllers\Support')
                ->group(base_path('routes/verified/support.php'));

            Route::middleware(['web', 'auth'])
                ->namespace('App\Http\Controllers\Infocards')
                ->group(base_path('routes/verified/infocards.php'));                     
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
