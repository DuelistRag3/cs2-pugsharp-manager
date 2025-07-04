<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        // Check if the request has a Bearer token
        if (!$token) {
            // If no Bearer token is present, return a 401 Unauthorized response
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {
            if (config('manager.api_bearer_token') !== $token) {
                // If the Bearer token does not match the expected token, return a 401 Unauthorized response
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        return $next($request);
    }
}
