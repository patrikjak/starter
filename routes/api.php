<?php

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\Metadata\Api\MetadataController;
use Patrikjak\Starter\Http\Controllers\Slugs\Api\SlugsController;
use Patrikjak\Starter\Http\Controllers\StaticPages\Api\StaticPagesController;
use Patrikjak\Starter\Http\Controllers\Users\Api\PermissionsController;
use Patrikjak\Starter\Http\Controllers\Users\Api\RolesController;
use Patrikjak\Starter\Http\Controllers\Users\Api\UsersController;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;

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
                Route::put('/{metadata}', [MetadataController::class, 'update'])
                    ->name('update')
                    ->can(BasePolicy::EDIT, Metadata::class);

                Route::get('/table-parts', [MetadataController::class, 'tableParts'])
                    ->name('table-parts')
                    ->can(BasePolicy::VIEW_ANY, Metadata::class);
            });
        }

        if ($usersEnabled) {
            Route::prefix('users')->name('users.')->group(static function (): void {
                Route::get('/table-parts', [UsersController::class, 'tableParts'])
                    ->name('table-parts')
                    ->can(BasePolicy::VIEW_ANY, User::class);
                
                Route::prefix('roles')->name('roles.')->group(static function (): void {
                    Route::get('/table-parts', [RolesController::class, 'tableParts'])
                        ->name('table-parts')
                        ->can(BasePolicy::VIEW_ANY, Role::class);
                    
                    Route::put('/{role}/permissions', [RolesController::class, 'syncPermissions'])
                        ->name('permissions')
                        ->can(RolePolicy::MANAGE, 'role');
                });
                
                Route::prefix('permissions')->name('permissions.')->group(static function (): void {
                    Route::get('/table-parts', [PermissionsController::class, 'tableParts'])
                        ->name('table-parts')
                        ->can(BasePolicy::VIEW_ANY, Permission::class);
                });
            });
        }
    });