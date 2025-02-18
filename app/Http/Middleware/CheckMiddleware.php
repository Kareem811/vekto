<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::check()) {
    //         return response()->json(["message" => "Unauthorized"], 401);
    //     }
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('sanctum')->check()) { // Use the correct guard
            return response()->json(["message" => "Unauthorized"], 401);
        }
        return $next($request);
    }
}
