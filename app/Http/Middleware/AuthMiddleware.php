<?php

namespace App\Http\Middleware;

use Attribute;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

#[Attribute()]
class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $user = auth()->guard()->user();
        $user->authorizeRoles(explode(',', $roles));
        return $next($request);
    }
}
