<?php

namespace App\Providers;

use App\Models\AgeRange;
use App\Models\Category;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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

        if (Schema::hasTable('age_ranges')) {
            view()->share('age_ranges', AgeRange::where('is_active', true)->get());
        }

        if (Schema::hasTable('categories')) {
            view()->share('categories', Category::where('is_active', true)->get());
        }

        if (Schema::hasTable('age_ranges')) {
            view()->composer('*', function (View $view) {
                $user = Auth::user();
                $selectedAgeRange = $user instanceof User
                    ? $user->age_range
                    : request()->cookie('age_range');

                $view->with('selected_age_range', AgeRange::where('slug', $selectedAgeRange)->where('is_active', true)->exists()
                    ? $selectedAgeRange
                    : null);
            });
        }
    }
}
