<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageSelection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user_locale = auth()->user()->locale;
            
            if (!in_array($user_locale, config('app.available_locales'))) {
                $user_locale = null;
            }
        } else {
            $user_locale = null;
        }

        $clientLocale = $user_locale ?: substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        if (in_array($clientLocale, config('app.available_locales'))) {
            App::setLocale($clientLocale);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }

        setlocale(LC_TIME, App::getLocale());

        return $next($request);
    }
}
