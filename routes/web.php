<?php

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\DashboardController;
use Patrikjak\Starter\Http\Controllers\Metadata\MetadataController;
use Patrikjak\Starter\Http\Controllers\Profile\ProfileController;
use Patrikjak\Starter\Http\Controllers\StaticPages\StaticPagesController;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;

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

            Route::middleware(StaticPagePolicy::can('create'))->group(static function (): void {
                Route::get('/create', [StaticPagesController::class, 'create'])->name('create');
                Route::get('/{staticPage}/edit', [StaticPagesController::class, 'edit'])->name('edit');
            });
    });
}

if ($metaDataEnabled) {
    Route::middleware(['web', 'auth'])->group(static function (): void {
        Route::get('/metadata', [MetadataController::class, 'index'])->name('metadata.index');
    });
}