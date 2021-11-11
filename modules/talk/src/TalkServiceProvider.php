<?php

namespace Talk;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Talk\Components\ConversationsList;
use Talk\Models\Conversation;
use Talk\Policies\ConversationPolicy;

class TalkServiceProvider extends ServiceProvider
{
    protected $policies = [
        Conversation::class => ConversationPolicy::class,
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
        $this->app->singleton('Talk', function() {
            return new \Talk\Talk();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Talk', \Talk\Facades\Talk::class);        
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'talk');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'talk');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/talk.php' => config_path('talk.php'),
        ], 'talk-config');
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
            'middleware' => array_merge([$interface], config('talk.middleware.' . $interface, ['auth'])),
            'prefix' => config('talk.url.' . $interface, 'talk'),
            'namespace' => 'Talk',
            'as' => 'talk.',
        ];
    }

    protected function loadViewComponents()
    {
        $this->loadViewComponentsAs('talk', [
            ConversationsList::class,
        ]);
    }
}