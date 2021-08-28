<?php

namespace Matcher;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MatcherServiceProvider extends ServiceProvider
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

        $this->loadViewComponents();
    }
    
    public function register()
    {

    }

    protected function registerFacades()
    {
        $this->app->singleton('Matcher', function() {
            return new \Matcher\Matcher();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Matcher', \Matcher\Facades\Matcher::class);        
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'Matcher');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'Matcher');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/matcher.php' => config_path('matcher.php'),
        ], 'matcher-config');
    }

    protected function registerRoutes()
    {
        Route::group($this->getRoutesConfiguration('web'), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        Route::group($this->getRoutesConfiguration('api'), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    protected function getRoutesConfiguration($interface = 'web')
    {
        return [
            'middleware' => array_merge([$interface], config('matcher.middleware', ['auth'])),
            'prefix' => config('matcher.url', 'groups'),
            'namespace' => 'Matcher',
            'as' => 'matcher.',
        ];
    }

    protected function loadViewComponents()
    {
        
    }
}