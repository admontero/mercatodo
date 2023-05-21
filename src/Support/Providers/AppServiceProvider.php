<?php

namespace Support\Providers;

use Illuminate\Support\ServiceProvider;
use Support\Middlewares\CacheProductResponseMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CacheProductResponseMiddleware::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
