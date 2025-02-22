<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => '/admins', 'as' => 'admin.'],
    function () {
        Route::controller(AuthController::class)->group(
            function () {
                Route::post('/register', 'register')
                    ->middleware('isGuest')
                    ->name('register');
                Route::post('/login', 'login')
                    ->middleware('isGuest')
                    ->name('login');
                Route::post('/logout', 'logout')
                    ->middleware(['auth:sanctum', 'isAdmin'])
                    ->name('logout');
            }
        );

        Route::apiResource('/products', ProductController::class)
            ->middleware(['auth:sanctum', 'isAdmin']);
    }
);
