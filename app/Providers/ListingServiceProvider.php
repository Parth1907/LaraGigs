<?php

namespace App\Providers;

use App\Services\ListingService;
use Illuminate\Support\ServiceProvider;

class ListingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('ListingService', ListingService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}