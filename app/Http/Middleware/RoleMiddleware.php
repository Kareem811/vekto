<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, $role): Response
    // {
    //     if (!Auth::check()) {
    //         return response()->json(["message" => "Unauthorized"], 401);
    //     }
    //     if (Auth::user()->role !== $role) {
    //         return response()->json(["message" => "Forbidden - Access Denied"], 403);
    //     }

    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, ...$roles): Response // Key change: ...$roles
    {
        if (!Auth::check()) {
            return response()->json(["message" => "Unauthorized"], 401); // Or redirect to login
        }

        // Check if the user has ANY of the required roles
        foreach ($roles as $role) {  // Iterate through provided roles
            if (Auth::user()->role === $role) { // Strict comparison is good practice
                return $next($request); // User has the required role, proceed
            }
        }

        return response()->json(["message" => "Forbidden - Access Denied"], 403); // Or redirect with an error message
    }
}
