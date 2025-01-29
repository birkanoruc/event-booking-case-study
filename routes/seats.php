<?php

use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;

/**
 * Koltuk işlemlerine ait endpointler
 *
 * POST     /api/seats/block    =>  Koltuk bloklama
 * DELETE   /api/seats/release  =>  Koltuk blok kaldırma
 */

Route::prefix("seats")->middleware("jwt.auth")->group(function () {
    Route::post('/block', [SeatController::class, 'block'])->name("seats.block");
    Route::delete('/release', [SeatController::class, 'release'])->name("seats.release");
});
