<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // API Rate Limiting
        RateLimiter::for('api', function (Request $request) {
            // Burada IP bazlı rate limit uygulanacak.
            // Her IP'ye dakikada 60 istek limiti tanımlanıyor.
            return Limit::perMinute(60)->by($request->ip());
        });

        Route::pattern('id', '[0-9]+'); // Tüm id parametreleri için filtreleme


    }
}
