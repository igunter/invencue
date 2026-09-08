<?php

use App\Http\Controllers\CategoryController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/age-range/{age_range}', function (string $age_range) {
    abort_unless(in_array($age_range, ['4-6', '7-9', '10-12', '13-15', '16-18'], true), 404);

    $user = Auth::user();
    if ($user instanceof User) {
        $user->update(['age_range' => $age_range]);

        if (request()->expectsJson()) {
            return response()->json(['age_range' => $age_range]);
        }

        return redirect()->route('dashboard');
    }

    if (request()->expectsJson()) {
        return response()->json(['age_range' => $age_range])
            ->withCookie(cookie('age_range', $age_range, 60 * 24 * 365));
    }

    return redirect()->route('login')->withCookie(cookie('age_range', $age_range, 60 * 24 * 365));
})->name('age-range.select');

Route::get('/', function () {
    $user = Auth::user();
    $selectedAgeRange = $user instanceof User
        ? $user->age_range
        : request()->cookie('age_range');

    return view('welcome', [
        'selected_age_range' => in_array($selectedAgeRange, ['4-6', '7-9', '10-12', '13-15', '16-18'], true)
            ? $selectedAgeRange
            : null,
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/categories', CategoryController::class)->only(['index', 'show'])->names('category');

require __DIR__.'/auth.php';
