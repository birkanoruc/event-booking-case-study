<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/**
 *   Oturum işlemlerine ait endpointler
 *
 *   POST /api/auth/register => Kayıt olma
 *   POST /api/auth/login    => Giriş yapma
 *   POST /api/auth/refresh  => Token yenileme
 *   POST /api/auth/logout   => Çıkış yapma
 */

Route::prefix("auth")->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name("auth.register");
    Route::post('login', [AuthController::class, 'login'])->name("auth.login");
});

Route::prefix("auth")->middleware("jwt.auth")->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh'])->name("auth.refresh");
    Route::post('logout', [AuthController::class, 'logout'])->name("auth.logout");
    Route::get('me', [AuthController::class, 'me'])->name("auth.me");
});
