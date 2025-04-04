<?php

use App\Http\Controllers\{AuthController, UserController};
use App\Http\Middleware\{IsLogged, JWTGuard};
use Illuminate\Support\Facades\Route;

Route::post('/islogged', [AuthController::class, 'isLogged']);

Route::group(['middleware' => IsLogged::class], function () {
    Route::post('/signup', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => JWTGuard::class], function () {
    Route::get('/profile', [UserController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
