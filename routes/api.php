<?php

use App\Http\Controllers\{
    AuthController,
    ChatController,
    ChatIdsController,
    DealController,
    FavoriteController,
    ProductController,
    UserController
};
use App\Http\Middleware\{IsLogged, JWTGuard};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::post('/islogged', [AuthController::class, 'isLogged']);

Route::group(['middleware' => IsLogged::class], function () {
    Route::post('/signup', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => JWTGuard::class], function () {

    Route::get('/profile/{id}', [UserController::class, 'index']);
    Route::put('/profile/update', [UserController::class, 'update']);
    Route::post('/imgupdate', [UserController::class, 'imageUpdating']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorite/{id}', [FavoriteController::class, 'favManager']);

    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/products/me', [ProductController::class, 'userProducts'])->name('product.userProducts');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::post('/products/filter', [ProductController::class, 'filter'])->name('product.filter');
    Route::get('/products/add', [ProductController::class, 'add'])->name('product.add');
    Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

    Route::post('/is_user/{id}', [ChatController::class, 'isUserId']);
    Route::post('/message', [ChatController::class, 'sendMessage']);
    Route::get('/message/history', [ChatController::class, 'history']);
    Route::get('/message/{id}', [ChatController::class, 'index']);
    Route::get('/chat_id/{id1}/{id2}', function ($id1, $id2) {
        return response(['data' => ChatIdsController::findOrMake($id1, $id2)]);
    });

    Route::post('/deal/{id}', [ChatController::class, 'store']);
    Route::post('/offer/accept/{id}', [ChatController::class, 'acceptDeal']);
    Route::post('/offer/refuse/{id}', [ChatController::class, 'refuseDeal']);

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
