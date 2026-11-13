<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OptimizationController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PublicController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin CMS Routes (Protected by Auth and explicit RBAC)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,editor,author'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Posts & Pages CRUD
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/duplicate', [PostController::class, 'duplicate'])->name('posts.duplicate');

    // Media Library (All logged-in authors can upload & pick media)
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::put('/media/{medium}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Taxonomies (Admin & Editor only)
    Route::middleware('role:admin,editor')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
        Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
        Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    });

    // Site Optimization Settings (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings/optimization', [OptimizationController::class, 'index'])->name('settings.optimization');
        Route::post('/settings/optimization', [OptimizationController::class, 'update'])->name('settings.optimization.update');
        Route::post('/settings/optimization/purge-revisions', [OptimizationController::class, 'purgeRevisions'])->name('settings.optimization.purge-revisions');
        Route::post('/settings/optimization/purge-orphans', [OptimizationController::class, 'purgeOrphans'])->name('settings.optimization.purge-orphans');
        Route::post('/settings/optimization/purge-logs', [OptimizationController::class, 'purgeLogs'])->name('settings.optimization.purge-logs');
        Route::post('/settings/optimization/clear-cache', [OptimizationController::class, 'clearCache'])->name('settings.optimization.clear-cache');

        // Theme Settings & Builders (Admin only)
        Route::get('/theme/general', [ThemeController::class, 'general'])->name('theme.general');
        Route::post('/theme/general', [ThemeController::class, 'updateGeneral'])->name('theme.general.update');
        Route::get('/theme/buttons', [ThemeController::class, 'buttons'])->name('theme.buttons');
        Route::post('/theme/buttons', [ThemeController::class, 'updateButtons'])->name('theme.buttons.update');
        Route::get('/theme/header', [ThemeController::class, 'header'])->name('theme.header');
        Route::post('/theme/header', [ThemeController::class, 'updateHeader'])->name('theme.header.update');
        Route::get('/theme/footer', [ThemeController::class, 'footer'])->name('theme.footer');
        Route::post('/theme/footer', [ThemeController::class, 'updateFooter'])->name('theme.footer.update');
    });
});

// Serve public storage files directly (avoids Laravel 11's signed storage.local collision and works without Windows symlinks)
Route::get('/media-files/{path}', function (string $path) {
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    if (! $disk->exists($path)) {
        abort(404);
    }

    return $disk->response($path);
})->where('path', '.*')->name('media.serve');

/*
|--------------------------------------------------------------------------
| Dynamic Content Catch-All (Public Frontend URL Route)
| Must remain at the end to allow explicit routes to match first.
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [PublicController::class, 'show'])->name('public.show')->where('slug', '[A-Za-z0-9\-\_]+');
