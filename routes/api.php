<?php

use App\Http\Controllers\{AuthController, ProductController, UserController};
use App\Http\Middleware\{IsLogged, JWTGuard};
use Illuminate\Support\Facades\Route;

Route::post('/islogged', [AuthController::class, 'isLogged']);

Route::group(['middleware' => IsLogged::class], function () {
    Route::post('/signup', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => JWTGuard::class], function () {
    Route::get('/profile', [UserController::class, 'index']);

    Route::get('/products/me', [ProductController::class, 'userProducts'])->name('product.index');
    Route::get('/product/add', [ProductController::class, 'add'])->name('product.add');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');

    Route::post('/logout', [AuthController::class, 'logout']);
});
