<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check()) {
            return response()->json([
                'message' => 'You are already logged in'
            ], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
