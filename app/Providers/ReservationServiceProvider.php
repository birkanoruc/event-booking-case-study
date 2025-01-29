<?php

namespace App\Providers;

use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Contracts\Services\ReservationInterface;
use App\Repositories\ReservationRepository;
use App\Services\ReservationService;
use Illuminate\Support\ServiceProvider;

class ReservationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ReservationInterface::class, ReservationService::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
