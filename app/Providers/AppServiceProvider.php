<?php

namespace App\Providers;

use App\Repositories\UrlRepository;
use App\Repositories\UrlRepositoryInterface;
use App\Services\UrlServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\UrlService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind UrlRepositoryInterface to UrlRepository
        $this->app->singleton(UrlRepositoryInterface::class, UrlRepository::class);

        // Bind UrlServiceInterface to UrlService
        $this->app->singleton(UrlServiceInterface::class, UrlService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
