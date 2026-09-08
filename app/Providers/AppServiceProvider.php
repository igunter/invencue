<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        $age_ranges = [
            '4-6',
            '7-9',
            '10-12',
            '13-15',
            '16-18',
        ];

        view()->share('age_ranges', $age_ranges);
    }
}
