<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestSanctum
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check()) {
            return response()->json([
                'message' => 'You are already authenticated.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
