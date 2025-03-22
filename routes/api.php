<?php

use Patrikjak\Starter\Http\Controllers\PageSlugs\Api\PageSlugsController;
use Patrikjak\Starter\Http\Controllers\StaticPages\Api\StaticPagesController;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;

Route::middleware(['web', 'auth'])
    ->prefix('api')
    ->name('api.')
    ->group(static function(): void {
        $staticPagesEnabled = config('pjstarter.features.static_pages');
        $metadataEnabled = config('pjstarter.features.metadata');

        if ($staticPagesEnabled) {
            Route::prefix('static-pages')
                ->name('static-pages.')
                ->group(static function (): void {
                    Route::middleware(StaticPagePolicy::can('update'))->group(static function () {
                        Route::post('/', [StaticPagesController::class, 'store'])->name('store');
                        Route::put('/{staticPage}', [StaticPagesController::class, 'update'])->name('update');
                        Route::delete('/{staticPage}', [StaticPagesController::class, 'destroy'])->name('destroy');
                    });

                    Route::get('/table-parts', [StaticPagesController::class, 'tableParts'])->name('table-parts');
            });

            Route::prefix('page-slugs')->name('page-slugs.')->group(static function (): void {
                Route::put('/{pageSlug}', [PageSlugsController::class, 'update'])->name('update');
            });
        }

        if ($metadataEnabled) {
            Route::name('metadata.')->prefix('metadata')->group(static function (): void {

            });
        }
    });