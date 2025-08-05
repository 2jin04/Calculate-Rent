<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Api\AuthController;



Route::get('/login', function () {
    return view('/auth/login');
})->name('login');

// Google OAuth
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Facebook OAuth  
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);

// Route::get('/auth/{provider}/callback', [AuthController::class, 'handleOAuthCallback']);

//Route cần đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    //Đăng xuất
    Route::get('/logout', [SocialAuthController::class, 'logout'])->name('logout');
    //Tạo phòng và gia nhập
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);
    Route::put('/rooms/{room}', [RoomController::class, 'update']);
    Route::post('/rooms/join', [RoomController::class, 'join']);
});