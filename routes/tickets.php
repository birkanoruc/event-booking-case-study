<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

/**
 *   Bilet işlemlerine ait endponitler
 *
 *   GET    /api/tickets                =>  Kullanıcıya ait biletleri listeleme
 *   GET    /api/tickets/{id}           =>  Kullanıcıya ait belirli bir bilet detay listesi
 *   GET    /api/tickets/{id}/download  =>  Kullanıcıya ait belirli bir bilet indirme
 *   POST   /api/tickets/{id}/transfer  =>  Kullanıcıya ait belirli bir bilet transferi
 */

Route::prefix("tickets")->middleware("jwt.auth")->group(function () {
    Route::get('/', [TicketController::class, 'index'])->name("tickets.index");
    Route::get('/{id}', [TicketController::class, 'show'])->name("tickets.show");
    Route::get('/{id}/download', [TicketController::class, 'download'])->name("tickets.download");
    Route::post('/{id}/transfer', [TicketController::class, 'transfer'])->name("tickets.transfer");
});
