<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\OrderController;
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

        Route::group(
            [
                'controller' => OrderController::class,
                'prefix' => '/orders',
                'as' => 'orders.',
                'middleware' => ['auth:sanctum', 'isAdmin']
            ],
            function () {
                Route::get('/active', 'getActiveOrders')
                    ->name('active');
                Route::get('/history', 'getOrderHistory')
                    ->name('history');
                Route::get('/{order}', 'show')
                    ->name('show');
                Route::patch('/{order}', 'updateStatus')
                    ->name('updateStatus');
            }
        );
    }
);
