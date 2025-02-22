<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\User\OrderController;
use App\Http\Controllers\Api\User\ProductController;
use Illuminate\Support\Facades\Route;

Route::as('user.')->group(function () {
    Route::controller(AuthController::class)->group(
        function () {
            Route::post('/register', 'register')
                ->middleware('isGuest')
                ->name('register');
            Route::post('/login', 'login')
                ->middleware('isGuest')
                ->name('login');
            Route::post('/logout', 'logout')
                ->middleware('auth:sanctum')
                ->name('logout');
        }
    );

    Route::apiResource('/products', ProductController::class)
        ->only(['index', 'show']);

    Route::group(
        ['controller' => CartController::class, 'prefix' => '/cart', 'as' => 'cart.', 'middleware' => 'auth:sanctum'],
        function () {
            Route::get('/', 'getCart')
                ->name('getProducts');
            Route::post('/', 'addProduct')
                ->name('addProduct');
            Route::put('/', 'replaceUserCart')
                ->name('replace');
            Route::delete('/{cartProduct}', 'deleteProduct')
                ->middleware('cartProductAccess')
                ->name('deleteProduct');
        }
    );

    Route::group(
        [
            'controller' => OrderController::class,
            'prefix' => '/orders',
            'as' => 'orders.',
            'middleware' => 'auth:sanctum'
        ],
        function () {
            Route::post('/', 'store')
                ->middleware('cartNotEmpty')
                ->name('store');
            Route::get('/active', 'getActiveOrders')
                ->name('active');
            Route::get('/history', 'getOrderHistory')
                ->name('history');
        }
    );
});
