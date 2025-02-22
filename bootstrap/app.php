<?php

use App\Http\Middleware\Admin\CheckIsAdminMiddleware;
use App\Http\Middleware\User\EnsureCartNotEmptyMiddleware;
use App\Http\Middleware\User\CartProductAccessMiddleware;
use App\Http\Middleware\IsGuestMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')->prefix('api')
                ->group(base_path('/routes/groups/users.php'))
                ->group(base_path('/routes/groups/admins.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->alias([
            'isGuest' => IsGuestMiddleware::class,
            'isAdmin' => CheckIsAdminMiddleware::class,
            'cartProductAccess' => CartProductAccessMiddleware::class,
            'cartNotEmpty' => EnsureCartNotEmptyMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
