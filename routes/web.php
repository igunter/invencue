<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameController;
use App\Models\AgeRange;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/age-range/{age_range}', function (string $age_range) {
    abort_unless(AgeRange::where('slug', $age_range)->where('is_active', true)->exists(), 404);

    $user = Auth::user();
    if ($user instanceof User) {
        $user->update(['age_range' => $age_range]);

        if (request()->expectsJson()) {
            return response()->json(['age_range' => $age_range]);
        }

        return back();
    }

    if (request()->expectsJson()) {
        return response()->json(['age_range' => $age_range])
            ->withCookie(cookie('age_range', $age_range, 60 * 24 * 365));
    }

    return back()->withCookie(cookie('age_range', $age_range, 60 * 24 * 365));
})->name('age-range.select');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/category', CategoryController::class)->only(['index', 'show'])->names('category');

Route::resource('/category/{category}/game', GameController::class)->only(['index', 'show'])->names('game');

require __DIR__.'/auth.php';
