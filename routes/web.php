<?php

use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameResultController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
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
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/game-results', [GameResultController::class, 'store'])->middleware('auth')->name('game-results.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/questions', [AdminQuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/{game:slug}', [AdminQuestionController::class, 'show'])->name('questions.show');
    Route::post('/questions/{game:slug}', [AdminQuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question}/edit', [AdminQuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{question}', [AdminQuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('questions.destroy');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::resource('/category', CategoryController::class)->only(['index', 'show'])->names('category');

Route::resource('/category/{category}/game', GameController::class)->only(['index', 'show'])->names('game');

require __DIR__.'/auth.php';
