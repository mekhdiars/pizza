<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartProductAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('sanctum')->user();
        $cartProduct = $request->route('cartProduct');

        if ($cartProduct->user->is($user) === false) {
            return response()->json([
                'message' => 'You are not allowed to access this cart'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
