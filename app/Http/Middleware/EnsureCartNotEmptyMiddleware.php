<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCartNotEmptyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->cartProducts->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
