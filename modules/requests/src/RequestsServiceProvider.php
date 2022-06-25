<?php

namespace Requests;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class RequestsServiceProvider extends ServiceProvider
{
    protected $policies = [
        
    ];

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerFacades();

        $this->registerResources();

        $this->registerPolicies();

        $this->registerRoutes();
    }

    protected function registerFacades()
    {
        $this->app->singleton('Requests', function() {
            return new \Requests\Requests();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Requests', \Requests\Facades\Requests::class);        
    }

    protected function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
    
    protected function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'requests');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'requests');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/requests.php' => config_path('requests.php'),
        ], 'requests-config');
    }

    protected function registerRoutes()
    {
        Route::group($this->getRoutesConfiguration('web'), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function getRoutesConfiguration($interface = 'web')
    {
        return [
            'middleware' => array_merge([$interface], config('requests.middleware.' . $interface, ['auth'])),
            'prefix' => config('requests.url.' . $interface, 'requests'),
            'namespace' => 'Requests',
            'as' => 'requests.',
        ];
    }    
}