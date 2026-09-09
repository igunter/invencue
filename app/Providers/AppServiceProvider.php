<?php

namespace App\Providers;

use App\Models\AgeRange;
use App\Models\Category;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        // Schema::hasTable() itself is a DB round-trip, so its result is cached
        // forever (cleared automatically whenever the app deploy flushes cache,
        // or manually after a fresh migration) rather than checked on every
        // single request — these tables exist for the lifetime of the app.
        if (Cache::rememberForever('schema-has-age-ranges-table', fn () => Schema::hasTable('age_ranges'))) {
            $ageRanges = Cache::remember('age-ranges-list', 3600, fn () => AgeRange::where('is_active', true)->ordered()->get());
            view()->share('age_ranges', $ageRanges);

            view()->composer('*', function (View $view) use ($ageRanges) {
                $user = Auth::user();
                $selectedAgeRange = $user instanceof User
                    ? $user->age_range
                    : request()->cookie('age_range');

                $view->with('selected_age_range', $ageRanges->contains('slug', $selectedAgeRange)
                    ? $selectedAgeRange
                    : null);
            });
        }

        if (Cache::rememberForever('schema-has-categories-table', fn () => Schema::hasTable('categories'))) {
            view()->share('categories', Cache::remember('categories-list', 3600, fn () => Category::where('is_active', true)->ordered()->get()));
        }
    }
}
