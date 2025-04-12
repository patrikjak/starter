<?php

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\Metadata\Api\MetadataController;
use Patrikjak\Starter\Http\Controllers\Slugs\Api\SlugsController;
use Patrikjak\Starter\Http\Controllers\StaticPages\Api\StaticPagesController;
use Patrikjak\Starter\Http\Controllers\Users\Api\PermissionsController;
use Patrikjak\Starter\Http\Controllers\Users\Api\RolesController;
use Patrikjak\Starter\Http\Controllers\Users\Api\UsersController;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Policies\BasePolicy;

Route::middleware(['web', 'auth'])
    ->prefix('api')
    ->name('api.')
    ->group(static function(): void {
        $staticPagesEnabled = config('pjstarter.features.static_pages');
        $usersEnabled = config('pjstarter.features.users');

        if ($staticPagesEnabled) {
            Route::prefix('static-pages')
                ->name('static-pages.')
                ->group(static function (): void {
                    Route::post('/', [StaticPagesController::class, 'store'])
                        ->name('store')
                        ->can(BasePolicy::CREATE, StaticPage::class);

                    Route::put('/{staticPage}', [StaticPagesController::class, 'update'])
                        ->name('update')
                        ->can(BasePolicy::EDIT, StaticPage::class);

                    Route::delete('/{staticPage}', [StaticPagesController::class, 'destroy'])
                        ->name('destroy')
                        ->can(BasePolicy::DELETE, StaticPage::class);

                    Route::get('/table-parts', [StaticPagesController::class, 'tableParts'])
                        ->name('table-parts')
                        ->can(BasePolicy::VIEW_ANY, StaticPage::class);
            });

            Route::prefix('slugs')->name('slugs.')->group(static function (): void {
                Route::put('/{slug}', [SlugsController::class, 'update'])->name('update');
            });
        }

        if ($staticPagesEnabled) {
            Route::prefix('metadata')->name('metadata.')->group(static function (): void {
                Route::put('/{metadata}', [MetadataController::class, 'update'])->name('update');

                Route::get('/table-parts', [MetadataController::class, 'tableParts'])->name('table-parts');
            });
        }

        if ($usersEnabled) {
            Route::prefix('users')->name('users.')->group(static function (): void {
                Route::get('/table-parts', [UsersController::class, 'tableParts'])->name('table-parts');
                
                Route::prefix('roles')->name('roles.')->group(static function (): void {
                    Route::get('/table-parts', [RolesController::class, 'tableParts'])->name('table-parts');
                });
                
                Route::prefix('permissions')->name('permissions.')->group(static function (): void {
                    Route::get('/table-parts', [PermissionsController::class, 'tableParts'])->name('table-parts');
                });
            });
        }
    });