<?php

namespace App\Providers;

use App\Nova\Metrics\ApprovedMemberships;
use App\Nova\Metrics\NewAppointments;
use App\Nova\Metrics\NewMemberships;
use App\Nova\Metrics\NewPeergroups;
use App\Nova\Metrics\NewReplies;
use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\OnlineUsers;
use App\Nova\Metrics\OpenPeergroups;
use App\Nova\Metrics\UsersPerDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($admin) {
            return in_array($admin->email, [
                Auth::guard('admin')->check(),
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new NewUsers,
            new UsersPerDay,
            new NewPeergroups,
            new NewMemberships,
            new NewReplies,
            new NewAppointments,
            new ApprovedMemberships,
            new OpenPeergroups,
            new OnlineUsers,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
