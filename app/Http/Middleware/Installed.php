<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Installed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if the application is installed
        if (!file_exists(storage_path('installed')) && !$request->is('install') && !$request->is('install/*')) {
            return redirect('/install');
        }

        // Prevent access to the installation routes if the application is already installed
        if (file_exists(storage_path('installed')) && ($request->is('install') || $request->is('install/*'))) {
            return redirect('/');
        }

        // if($request->is('install') || $request->is('install/*') && file_exists(storage_path('installed'))) {
        //     return redirect('/');
        // }

        // if($request->is('install') || $request->is('install/*') && !file_exists(storage_path('installed'))) {
        //     return $next($request);
        // }

        return $next($request);
    }
}
