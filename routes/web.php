<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/image/{filename}', function ($filename) {
    $path = storage_path('app/public/Products/' . $filename);

    if (!file_exists($path)) {
        return response(null, 404);
    }

    return Response::file($path, [
        'Access-Control-Allow-Origin' => 'http://localhost:5173',
    ]);
});
