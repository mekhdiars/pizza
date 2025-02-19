<?php

use App\Http\Middleware\EnsureCartNotEmptyMiddleware;
use App\Http\Middleware\GuestSanctum;
use App\Http\Middleware\User\CartProductAccessMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')->prefix('api')
                ->group(base_path('/routes/groups/users.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest.sanctum' => GuestSanctum::class,
            'cartProductAccess' => CartProductAccessMiddleware::class,
            'cartNotEmpty' => EnsureCartNotEmptyMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
