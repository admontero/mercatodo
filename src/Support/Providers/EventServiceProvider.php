<?php

namespace Support\Providers;

use Domain\Category\Models\Category;
use Domain\Category\Observers\CategoryObserver;
use Domain\Order\Events\OrderCanceled;
use Domain\Order\Events\OrderCreated;
use Domain\Order\Listeners\RestoreProductStock;
use Domain\Order\Listeners\SubtractProductStock;
use Domain\Product\Models\Product;
use Domain\Product\Observers\ProductObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            SubtractProductStock::class,
        ],
        OrderCanceled::class => [
            RestoreProductStock::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
