<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\User\UserController;

Route::get('/', function () {
    return response()->json(['message' => 'OK']);
});

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/me', function (Request $request) {
        return response()->json($request -> auth);
    });
});


Route::middleware('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/users/verify/{id}', [UserController::class, 'verify']);
});
