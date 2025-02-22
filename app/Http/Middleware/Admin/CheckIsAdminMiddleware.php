<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('sanctum')->user();
        if (!$user->isAdmin()) {
            return response()->json([
                'message' => 'Access denied'
            ], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
