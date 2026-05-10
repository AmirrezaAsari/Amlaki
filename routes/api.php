<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;

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
    Route::get('/users', function () {
       return response()->json('Will be developed');
    });
});
