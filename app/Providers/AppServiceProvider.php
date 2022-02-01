<?php

namespace App\Providers;

use App\Helpers\Facades\EasyDate;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('components.layout.*', function ($view) {
            // Load the current user with receipts to show in the header (mail symbol)
            $view->with('currentUser', User::where('id', auth()->id())->with(['receipts' => function ($query) {
                $query->orderBy('updated_at', 'desc')->with('conversation');
            }])->get()->first());
        });
    }
}
