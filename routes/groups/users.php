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
                ->middleware('guest.sanctum')
                ->name('register');
            Route::post('/login', 'login')
                ->middleware('guest.sanctum')
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

    Route::apiResource('/orders', OrderController::class)
        ->except(['update', 'destroy'])
        ->middleware('auth:sanctum')
        ->middlewareFor('store', 'cartNotEmpty');
});
