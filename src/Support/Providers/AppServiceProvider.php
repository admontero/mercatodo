<?php

namespace Support\Providers;

use Domain\Shared\Contracts\PaymentFactoryInterface;
use Domain\Shared\Contracts\ReportFactoryInterface;
use Illuminate\Support\ServiceProvider;
use Services\Payments\PaymentFactory;
use Services\Reports\ReportFactory;
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
        $this->app->bind(ReportFactoryInterface::class, ReportFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
