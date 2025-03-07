<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade::component('card', View\Components\Card::class);
        // Blade::component('table', View\Components\Table::class);
        // Blade::component('stats-card', View\Components\StatsCard::class);
        // Blade::component('navbar', View\Components\Navbar::class);
        // Blade::component('sidebar', View\Components\Sidebar::class);
        // Blade::component('alert', View\Components\Alert::class);
    }
}
