<?php

namespace Talk;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Talk\Components\ConversationsList;
use Talk\Facades\Talk as TalkFacade;

class TalkServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();

        $this->registerRoutes();

        $this->loadViewComponents();

    }
    
    public function register()
    {
        $this->app->bind('talk', function() {
            return new Talk();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Talk', TalkFacade::class);
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
            'middleware' => array_merge([$interface], config('talk.middleware')),
            'prefix' => config('talk.url', 'talk'),
            'namespace' => 'Talk',
            'as' => 'talk.',
        ];
    }

    protected function loadViewComponents()
    {
        //['conversations-list' => ConversationsList::class],

        $this->loadViewComponentsAs('talk', [
            ConversationsList::class,
        ]);
    }
}