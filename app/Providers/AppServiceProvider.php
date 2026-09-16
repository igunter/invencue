<?php

namespace App\Providers;

use App\Models\AgeRange;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Collection;
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

        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        // Resolved lazily via a composer (run just before each view renders)
        // rather than eagerly here in boot() — boot() runs as soon as the app
        // is built, which can be *before* migrations have run (e.g. a fresh
        // install, or a test booting an in-memory database), so an eager
        // Schema::hasTable() check here can cache a stale "false" forever.
        view()->composer('*', function (View $view) {
            $ageRanges = $this->ageRanges();

            $user = Auth::user();
            $selectedAgeRange = $user instanceof User
                ? $user->age_range
                : request()->cookie('age_range');

            $view->with('age_ranges', $ageRanges);
            $view->with('selected_age_range', $ageRanges->contains('slug', $selectedAgeRange)
                ? $selectedAgeRange
                : null);
            $view->with('categories', $this->categories());
        });
    }

    /**
     * Active age ranges, or an empty collection if the table doesn't exist yet.
     */
    protected function ageRanges(): Collection
    {
        if (!$this->hasTable('age_ranges')) {
            return collect();
        }

        return Cache::remember('age-ranges-list', 3600, fn () => AgeRange::where('is_active', true)->ordered()->get());
    }

    /**
     * Active categories, or an empty collection if the table doesn't exist yet.
     */
    protected function categories(): Collection
    {
        if (!$this->hasTable('categories')) {
            return collect();
        }

        return Cache::remember('categories-list', 3600, fn () => Category::where('is_active', true)->ordered()->get());
    }

    /**
     * Schema::hasTable() itself is a DB round-trip, so a true result is cached
     * forever (cleared automatically whenever the app deploy flushes cache, or
     * manually after a fresh migration) — these tables exist for the lifetime
     * of the app once created. A false result is never cached, so the check
     * is retried on the next request until the table shows up.
     */
    protected function hasTable(string $table): bool
    {
        return Cache::get("schema-has-{$table}-table") ?? tap(Schema::hasTable($table), function (bool $exists) use ($table) {
            if ($exists) {
                Cache::forever("schema-has-{$table}-table", true);
            }
        });
    }
}
