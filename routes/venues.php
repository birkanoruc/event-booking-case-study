<?php

use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

/**
 *  Mekan işlemlerine ait endpointler
 *  GET  /api/venues/{id}/seats  =>  Mekan koltuk detayları
 */

Route::prefix("venues")->middleware("jwt.auth")->group(function () {
    Route::get('/{id}/seats', [VenueController::class, 'seats'])->name("venues.seats");
});
