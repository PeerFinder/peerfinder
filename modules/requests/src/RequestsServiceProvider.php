<?php

namespace Requests;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

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

        $this->loadViewComponents();
    }

    protected function registerFacades()
    {
        $this->app->singleton('Requests', function() {
            return new \Requests\Requests();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Requests', \Requests\Facades\Requests::class);        
    }
}