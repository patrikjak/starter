<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\DashboardController;
use Patrikjak\Starter\Http\Controllers\Metadata\MetadataController;
use Patrikjak\Starter\Http\Controllers\Profile\ProfileController;
use Patrikjak\Starter\Http\Controllers\StaticPages\StaticPagesController;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;

$dashboardEnabled = config('pjstarter.features.dashboard');
$profileEnabled = config('pjstarter.features.profile');
$staticPagesEnabled = config('pjstarter.features.static_pages');

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

if ($staticPagesEnabled) {
    Route::middleware(['web', 'auth'])
        ->prefix('metadata')
        ->name('metadata.')
        ->group(static function (): void {
            Route::get('/', [MetadataController::class, 'index'])->name('index');
            Route::get('/{metadata}', [MetadataController::class, 'show'])->name('show');
            Route::get('/{metadata}/edit', [MetadataController::class, 'edit'])->name('edit');
    });
}

Route::get('bengoro', function (\Patrikjak\Starter\Services\Slugs\SlugsService $slugsService, Request $request) {
    dd($slugsService->getSluggableFromUrl($request->url()));
});