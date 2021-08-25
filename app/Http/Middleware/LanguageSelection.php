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
        $clientLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        if (in_array($clientLocale, ['en', 'de'])) {
            App::setLocale($clientLocale);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }

        setlocale(LC_TIME, App::getLocale());

        return $next($request);
    }
}
