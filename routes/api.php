<?php

use Patrikjak\Starter\Http\Controllers\PageSlugs\Api\PageSlugsController;

Route::middleware(['web', 'auth'])
    ->prefix('api')
    ->name('api.')
    ->group(static function(): void {
        Route::name('metadata.')->prefix('metadata')->group(static function (): void {

        });
        
        Route::prefix('page-slugs')->name('page-slugs.')->group(static function (): void {
            Route::post('/', [PageSlugsController::class, 'store'])->name('store');
            Route::put('/{page}', [PageSlugsController::class, 'update'])->name('update');
            Route::delete('/{page}', [PageSlugsController::class, 'destroy'])->name('destroy');

            Route::get('/table-parts', [PageSlugsController::class, 'tableParts'])->name('table-parts');
        });
    });