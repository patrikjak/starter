<?php

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\DashboardController;
use Patrikjak\Starter\Http\Controllers\Metadata\MetadataController;
use Patrikjak\Starter\Http\Controllers\PageSlugs\PageSlugsController;
use Patrikjak\Starter\Http\Controllers\Profile\ProfileController;
use Patrikjak\Starter\Http\Controllers\StaticPages\StaticPagesController;

$dashboardEnabled = config('pjstarter.features.dashboard');
$profileEnabled = config('pjstarter.features.profile');
$staticPagesEnabled = config('pjstarter.features.static_pages');
$metaDataEnabled = config('pjstarter.features.metadata');

if ($dashboardEnabled) {
    Route::middleware(['web', 'auth'])->group(static function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
}

if ($profileEnabled) {
    Route::middleware(['web', 'auth'])->group(static function (): void {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    });
}

if ($staticPagesEnabled) {
    Route::middleware(['web', 'auth'])
        ->prefix('static-pages')
        ->name('static-pages.')
        ->group(static function (): void {
            Route::get('/', [StaticPagesController::class, 'index'])->name('index');
            Route::get('/create', [StaticPagesController::class, 'create'])->name('create');
            Route::get('/{staticPage}/edit', [StaticPagesController::class, 'edit'])->name('edit');
    });
}

if ($metaDataEnabled) {
    Route::middleware(['web', 'auth'])->group(static function (): void {
        Route::get('/metadata', [MetadataController::class, 'index'])->name('metadata.index');

        Route::prefix('page-slugs')->name('page-slugs.')->group(static function (): void {
            Route::get('/', [PageSlugsController::class, 'index'])->name('index');
            Route::get('/create', [PageSlugsController::class, 'create'])->name('create');
            Route::get('/{page}/edit', [PageSlugsController::class, 'edit'])->name('edit');
        });
    });
}