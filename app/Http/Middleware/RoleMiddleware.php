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

    // }
    public function handle(Request $request, Closure $next): Response // Key change: ...$roles
    {
        if (!Auth::check()) {
            return response()->json(["message" => "Unauthorized"], 401); // Fixed status code
        }
        if (Auth::user()->role === "superadmin") {
            return $next($request);
        }
        return response()->json(["message" => "Forbidden"], 403);
    }
}
