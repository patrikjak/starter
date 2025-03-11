<?php

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\DashboardController;
use Patrikjak\Starter\Http\Controllers\ProfileController;

$dashboardEnabled = config('pjstarter.features.dashboard');
$profileEnabled = config('pjstarter.features.profile');

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
