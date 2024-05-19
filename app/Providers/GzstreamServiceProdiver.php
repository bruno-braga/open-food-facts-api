<?php

namespace App\Providers;

use App\Services\GzstreamService;
use App\Services\GzstreamServiceInterface;
use Illuminate\Support\ServiceProvider;

class GzstreamServiceProdiver extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(
            GzstreamServiceInterface::class,
            GzstreamService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
