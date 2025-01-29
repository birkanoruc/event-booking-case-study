<?php

namespace App\Providers;

use App\Contracts\Repositories\VenueRepositoryInterface;
use App\Contracts\Services\VenueInterface;
use App\Repositories\VenueRepository;
use App\Services\VenueService;
use Illuminate\Support\ServiceProvider;

class VenueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VenueInterface::class, VenueService::class);
        $this->app->bind(VenueRepositoryInterface::class, VenueRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
