<?php

namespace Support\Providers;

use Domain\Shared\Contracts\PaymentFactoryInterface;
use Illuminate\Support\ServiceProvider;
use Services\PaymentFactory;
use Support\Middlewares\CacheProductResponseMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CacheProductResponseMiddleware::class);
        $this->app->bind(PaymentFactoryInterface::class, PaymentFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
