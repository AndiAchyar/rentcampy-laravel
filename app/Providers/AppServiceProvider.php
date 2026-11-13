<?php

namespace App\Providers;

use App\Support\Cart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');

        // Jumlah item keranjang untuk badge di header dan sidebar.
        View::composer(['partials.header', 'components.dashboard'], function ($view) {
            $view->with('cartCount', Cart::count());
        });
    }
}
