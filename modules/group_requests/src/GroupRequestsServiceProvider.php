<?php

namespace GroupRequests;

use GroupRequests\Policies\GroupRequestPolicy;
use GroupRequests\Models\GroupRequest;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class GroupRequestsServiceProvider extends ServiceProvider
{
    protected $policies = [
        GroupRequest::class => GroupRequestPolicy::class,
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
        $this->app->singleton('GroupRequests', function() {
            return new \GroupRequests\GroupRequests();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('GroupRequests', \GroupRequests\Facades\GroupRequests::class);        
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'group_requests');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'group_requests');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/group_requests.php' => config_path('group_requests.php'),
        ], 'group-requests-config');
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
            'middleware' => array_merge([$interface], config('group_requests.middleware.' . $interface, ['auth'])),
            'prefix' => config('group_requests.url.' . $interface, 'requests'),
            'namespace' => 'GroupRequests',
            'as' => 'group_requests.',
        ];
    }    
}