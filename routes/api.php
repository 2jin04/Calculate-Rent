<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {

    // Lấy URL đăng nhập
    Route::get('/google/url', [AuthController::class, 'getGoogleSignInUrl']);
    Route::get('/facebook/url', [AuthController::class, 'getFacebookSignInUrl']);
    
    // Xử lý callback (dành cho testing, thực tế sẽ handle ở React)
    Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('/facebook/callback', [AuthController::class, 'handleFacebookCallback']);
    
    // Routes cần authentication
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});