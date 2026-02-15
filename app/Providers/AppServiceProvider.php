<?php

namespace App\Providers;

use App\Services\InvoiceService;
use App\Services\Scraper\ScraperManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ScraperManager::class);
        $this->app->singleton(InvoiceService::class);
    }

    public function boot(): void
    {
        //
    }
}
