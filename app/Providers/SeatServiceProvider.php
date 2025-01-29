<?php

namespace App\Providers;

use App\Contracts\Repositories\SeatRepositoryInterface;
use App\Contracts\Services\SeatInterface;
use App\Repositories\SeatRepository;
use App\Services\SeatService;
use Illuminate\Support\ServiceProvider;

class SeatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SeatInterface::class, SeatService::class);
        $this->app->bind(SeatRepositoryInterface::class, SeatRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
