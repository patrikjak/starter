<?php

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\DashboardController;
use Patrikjak\Starter\Http\Controllers\Metadata\MetadataController;
use Patrikjak\Starter\Http\Controllers\Metadata\PagesController;
use Patrikjak\Starter\Http\Controllers\ProfileController;

$dashboardEnabled = config('pjstarter.features.dashboard');
$profileEnabled = config('pjstarter.features.profile');
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

if ($metaDataEnabled) {
    Route::middleware(['web', 'auth'])->group(static function (): void {
        Route::get('/metadata', [MetadataController::class, 'index'])->name('metadata.index');
        Route::get('/pages', [PagesController::class, 'index'])->name('metadata.pages.index');
    });
}
