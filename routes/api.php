<?php

use Patrikjak\Starter\Http\Controllers\Metadata\Api\PagesController;

Route::middleware(['web', 'auth'])
    ->prefix('api')
    ->name('api.')
    ->group(static function(): void {
        Route::name('metadata.')->prefix('metadata')->group(static function (): void {
            Route::name('pages.')->prefix('pages')->group(static function (): void {
                Route::post('/', [PagesController::class, 'store'])->name('store');
                Route::put('/{page}', [PagesController::class, 'update'])->name('update');
                Route::delete('/{page}', [PagesController::class, 'destroy'])->name('destroy');

                Route::get('/table-parts', [PagesController::class, 'tableParts'])->name('table-parts');
            });
        });
    });