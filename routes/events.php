<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

/**
 *   Etkinlik işlemlerine ait endpointler
 *
 *   GET     /api/events                 =>  Tüm etkinliklerin listelenmesi
 *   GET     /api/events/{id}            =>  Belirli etkinlik detaylarının listelenmesi
 *   POST    /api/events                 =>  Etkinlik oluşturma
 *   PUT     /api/events/{id}            =>  Etkinlik güncelleme
 *   DELETE  /api/events/{id}            =>  Etkinlik Silme
 *   GET     /api/events/{id}/seats      =>  Belirli bir etkinliğin koltuk detaylarının listelenmesi
 */

Route::prefix("events")->middleware("jwt.auth")->group(function () {
    Route::get('/', [EventController::class, 'index'])->name("events.index");
    Route::get('/{id}', [EventController::class, 'show'])->name("events.show");
    Route::post('/', [EventController::class, 'store'])->name("events.store");
    Route::put('/{id}', [EventController::class, 'update'])->name("events.update");
    Route::delete('/{id}', [EventController::class, 'destroy'])->name("events.destroy");
    Route::get('/{id}/seats', [EventController::class, 'seats'])->name("events.seats");
});
