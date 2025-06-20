<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class farmer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard()->check()) {
            if (auth()->guard()->user()->role !=='farmer') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access Denied',
                ], 401);
            }
        }
        return $next($request);
    }
}