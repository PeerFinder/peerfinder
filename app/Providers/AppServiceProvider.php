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
            $user = User::where('id', auth()->id())->with(['receipts' => function ($query) {
                $query->orderBy('updated_at', 'desc')->with('conversation');
            }])->withCount('received_invitations')->get()->first();

            // Load the current user with receipts to show in the header (mail symbol)
            $view->with('currentUser', $user);

            if ($user) {
                $dashboardCounter = $user->received_invitations_count;
                $view->with('dashboardCounter', $dashboardCounter);
            }
        });
    }
}
