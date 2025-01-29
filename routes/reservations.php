<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

/**
 *   Rezervasyon işlemlerine ait endpointler
 *
 *   GET     /api/reservations               =>  Kullanıcının rezervasyonlarını listeleme
 *   GET     /api/reservations/{id}          =>  Belirli rezervasyon detaylarının listelenmesi
 *   POST    /api/reservations Yeni          =>  Rezervasyon oluşturma
 *   DELETE  /api/reservations/{id}          =>  Rezervasyon iptali
 *   POST    /api/reservations/{id}/confirm  =>  Rezervasyon onaylama
 */

Route::prefix("reservations")->middleware("jwt.auth")->group(function () {
    Route::get('/', [ReservationController::class, 'index'])->name("reservations.index");
    Route::get('/{id}', [ReservationController::class, 'show'])->name("reservations.show");
    Route::post('/', [ReservationController::class, 'store'])->name("reservations.store");
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name("reservations.destroy");
    Route::post('/{id}/confirm', [ReservationController::class, 'confirm'])->name("reservations.confirm");
});
