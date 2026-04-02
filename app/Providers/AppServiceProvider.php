<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Repositories\Contracts\FoodRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\FoodRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FoodRepositoryInterface::class, FoodRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('cartCount', auth()->check()
                ? CartItem::where('user_id', auth()->id())->sum('quantity')
                : 0
            );
        });
    }
}
