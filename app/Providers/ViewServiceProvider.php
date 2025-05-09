<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\TrendingComposer;
use App\Http\View\Composers\PeopleToConnectComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('admin.components.right-sidebar', PeopleToConnectComposer::class);
        View::composer(['admin.components.right-sidebar', 'user.explore'], TrendingComposer::class);

    }
}
