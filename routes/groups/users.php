<?php

use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['controller' => AuthController::class, 'prefix' => 'users', 'as' => 'user.'],
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
