<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $globalCartCount = 0;
            if (auth()->check()) {
                $globalCartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
            }
            $view->with('globalCartCount', $globalCartCount);
        });
    }
}