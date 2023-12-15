<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthendication
{
    /**
     * Handle an incoming request.
     * Every API calls will be authendicated here
     * Default auth toke is xplor_inv_api.
     * It can be overridden in the env file
     * if the bearer token is valid as defined in the .env file it will allow
     * else it will show unauthendicated error 
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if ($token && $token == env('XPLOR_API_TOKEN', 'xplor_inv_api')) {
            return $next($request);
        } else {
            return response([
                'message' => 'Unauthenticated'
            ], 403);
        }
    }
}
