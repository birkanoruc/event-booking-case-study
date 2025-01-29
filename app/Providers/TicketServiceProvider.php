<?php

namespace App\Providers;

use App\Contracts\Repositories\TicketRepositoryInterface;
use App\Contracts\Services\TicketInterface;
use App\Repositories\TicketRepository;
use App\Services\TicketService;
use Illuminate\Support\ServiceProvider;

class TicketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TicketInterface::class, TicketService::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
