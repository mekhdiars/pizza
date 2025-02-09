<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\ProductController;

Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);
