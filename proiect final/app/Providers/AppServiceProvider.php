<?php

namespace App\Providers;

use App\Contracts\ProductRepositoryInterface;
use App\Events\OrderPlaced;
use App\Listeners\LogOrderActivity;
use App\Listeners\SendOrderConfirmation;
use App\Listeners\UpdateDashboardStats;
use App\Notifications\Channels\Email\EmailNotificationFactory;
use App\Notifications\Factory\OrderNotificationFactory;
use App\Repositories\CachingProductProxy;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class);

        // Abstract Factory: bind the default notification channel
        $this->app->bind(OrderNotificationFactory::class, EmailNotificationFactory::class);

        // Proxy Pattern: bind the caching proxy as default product repository
        $this->app->bind(ProductRepositoryInterface::class, CachingProductProxy::class);
    }

    public function boot(): void
    {
        // Observer Pattern: register event listeners
        Event::listen(OrderPlaced::class, SendOrderConfirmation::class);
        Event::listen(OrderPlaced::class, UpdateDashboardStats::class);
        Event::listen(OrderPlaced::class, LogOrderActivity::class);

        View::composer('*', function ($view) {
            $cartService = app(CartService::class);
            $view->with('cartCount', $cartService->getItemCount());
            $view->with('wishlistCount', Auth::check() ? Auth::user()->wishlist()->count() : 0);
        });
    }
}
