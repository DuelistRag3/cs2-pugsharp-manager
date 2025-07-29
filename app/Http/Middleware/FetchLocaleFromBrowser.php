<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FetchLocaleFromBrowser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accept = $request->headers->get('Accept-Language');

        $locale = strtolower(substr(explode(',', $accept)[0], 0, 2));

        $supported = config('app.supported_locales', ['en', 'de']);
        if (in_array($locale, $supported)) {
            app()->setLocale($locale);
        }
        else {
            app()->setLocale(config('app.fallback_locale', 'en'));
        }
        return $next($request);
    }
}
