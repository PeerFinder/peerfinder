<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Carbon\Carbon;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class OnlineUsers extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $total_users = User::where('email_verified_at', '<>', null)->count();
        $online_users = User::where('email_verified_at', '<>', null)->where('last_seen', '>', Carbon::now()->subMinutes(config('user.timeouts.is_online')))->count();

        return $this->result([
            'Online' => $online_users,
            'Offline' => $total_users - $online_users,
        ])->colors([
            'Online' => '#00bb00',
            'Offline' => '#dd0000',
        ]);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'online-users';
    }
}
