<?php

use App\Http\Controllers\{AuthController};
use Illuminate\Support\Facades\Route;


Route::post('/signup', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
