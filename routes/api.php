<?php

use App\Http\Controllers\{AuthController};
use App\Http\Middleware\JWTGuard;
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => JWTGuard::class], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
