<?php

use App\Http\Controllers\{AuthController, ProductController, UserController};
use App\Http\Middleware\{IsLogged, JWTGuard};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::post('/islogged', [AuthController::class, 'isLogged']);

Route::group(['middleware' => IsLogged::class], function () {
    Route::post('/signup', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => JWTGuard::class], function () {
    Route::get('/profile', [UserController::class, 'index']);

    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/products/me', [ProductController::class, 'userProducts'])->name('product.userProducts');
    Route::get('/product/add', [ProductController::class, 'add'])->name('product.add');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::post('/product/filter', [ProductController::class, 'filter'])->name('product.filter');
    Route::delete('/product/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/image/{filename}', function ($filename) {
    $path = storage_path('app/public/Products/' . $filename);

    if (!file_exists($path)) {
        return response(null, 404);
    }

    return Response::file($path, [
        'Access-Control-Allow-Origin' => 'http://localhost:5173',
    ]);
});
