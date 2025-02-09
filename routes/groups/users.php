<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\ProductController;
use Illuminate\Support\Facades\Route;

Route::as('user.')->group(function () {
    Route::group(
        ['controller' => AuthController::class, 'prefix' => 'users'],
        function () {
            Route::post('register', 'register')
                ->middleware('guest.sanctum')
                ->name('register');
            Route::post('login', 'login')
                ->middleware('guest.sanctum')
                ->name('login');
            Route::post('logout', 'logout')
                ->middleware('auth:sanctum')
                ->name('logout');
        }
    );

    Route::apiResource('products', ProductController::class)
        ->only(['index', 'show']);
});
