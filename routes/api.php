<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\Articles\Api\ArticleCategoriesController;
use Patrikjak\Starter\Http\Controllers\Articles\Api\ArticlesController;
use Patrikjak\Starter\Http\Controllers\Authors\Api\AuthorsController;
use Patrikjak\Starter\Http\Controllers\Metadata\Api\MetadataController;
use Patrikjak\Starter\Http\Controllers\Slugs\Api\SlugsController;
use Patrikjak\Starter\Http\Controllers\StaticPages\Api\StaticPagesController;
use Patrikjak\Starter\Http\Controllers\Users\Api\PermissionsController;
use Patrikjak\Starter\Http\Controllers\Users\Api\RolesController;
use Patrikjak\Starter\Http\Controllers\Users\Api\UsersController;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;

$authEnabled = config('pjstarter.features.auth');
$middleware = $authEnabled ? ['web', 'auth'] : ['web'];

Route::middleware($middleware)
    ->prefix('admin/api')
    ->name('admin.api.')
    ->group(static function() use ($authEnabled): void {
        $staticPagesEnabled = config('pjstarter.features.static_pages');
        $usersEnabled = config('pjstarter.features.users');
        $articlesEnabled = config('pjstarter.features.articles');

        if ($staticPagesEnabled) {
            Route::prefix('static-pages')
                ->name('static-pages.')
                ->group(static function () use ($authEnabled): void {
                    $storeRoute = Route::post('/', [StaticPagesController::class, 'store'])->name('store');
                    if ($authEnabled) {
                        $storeRoute->can(BasePolicy::CREATE, StaticPage::class);
                    }

                    $updateRoute = Route::put('/{staticPage}', [StaticPagesController::class, 'update'])->name('update');
                    if ($authEnabled) {
                        $updateRoute->can(BasePolicy::EDIT, StaticPage::class);
                    }

                    $destroyRoute = Route::delete('/{staticPage}', [StaticPagesController::class, 'destroy'])->name('destroy');
                    if ($authEnabled) {
                        $destroyRoute->can(BasePolicy::DELETE, StaticPage::class);
                    }

                    $tablePartsRoute = Route::get('/table-parts', [StaticPagesController::class, 'tableParts'])->name('table-parts');
                    if ($authEnabled) {
                        $tablePartsRoute->can(BasePolicy::VIEW_ANY, StaticPage::class);
                    }
            });
        }

        if ($staticPagesEnabled || $articlesEnabled) {
            Route::prefix('metadata')->name('metadata.')->group(static function () use ($authEnabled): void {
                $updateRoute = Route::put('/{metadata}', [MetadataController::class, 'update'])->name('update');
                if ($authEnabled) {
                    $updateRoute->can(BasePolicy::EDIT, Metadata::class);
                }

                $tablePartsRoute = Route::get('/table-parts', [MetadataController::class, 'tableParts'])->name('table-parts');
                if ($authEnabled) {
                    $tablePartsRoute->can(BasePolicy::VIEW_ANY, Metadata::class);
                }
            });

            Route::prefix('slugs')->name('slugs.')->group(static function (): void {
                Route::put('/{slug}', [SlugsController::class, 'update'])->name('update');
            });
        }

        if ($usersEnabled) {
            Route::prefix('users')->name('users.')->group(static function () use ($authEnabled): void {
                $tablePartsRoute = Route::get('/table-parts', [UsersController::class, 'tableParts'])->name('table-parts');
                if ($authEnabled) {
                    $tablePartsRoute->can(BasePolicy::VIEW_ANY, User::class);
                }

                Route::prefix('roles')->name('roles.')->group(static function () use ($authEnabled): void {
                    $tablePartsRoute = Route::get('/table-parts', [RolesController::class, 'tableParts'])->name('table-parts');
                    if ($authEnabled) {
                        $tablePartsRoute->can(BasePolicy::VIEW_ANY, Role::class);
                    }

                    $syncPermissionsRoute = Route::put('/{role}/permissions', [RolesController::class, 'syncPermissions'])->name('permissions');
                    if ($authEnabled) {
                        $syncPermissionsRoute->can(RolePolicy::MANAGE, 'role');
                    }
                });

                Route::prefix('permissions')->name('permissions.')->group(static function () use ($authEnabled): void {
                    $tablePartsRoute = Route::get('/table-parts', [PermissionsController::class, 'tableParts'])->name('table-parts');
                    if ($authEnabled) {
                        $tablePartsRoute->can(BasePolicy::VIEW_ANY, Permission::class);
                    }
                });
            });
        }

        if ($articlesEnabled) {
            Route::prefix('articles')->name('articles.')->group(static function () use ($authEnabled): void {
                $storeRoute = Route::post('/', [ArticlesController::class, 'store'])->name('store');
                if ($authEnabled) {
                    $storeRoute->can(BasePolicy::CREATE, Article::class);
                }

                Route::post('/upload-image', [ArticlesController::class, 'uploadImage'])->name('upload-image');
                Route::post('/fetch-image', [ArticlesController::class, 'fetchImage'])->name('fetch-image');

                $contentRoute = Route::get('/{article}/content', [ArticlesController::class, 'content'])->name('content');
                if ($authEnabled) {
                    $contentRoute->can(BasePolicy::EDIT, Article::class);
                }

                $tablePartsRoute = Route::get('/table-parts', [ArticlesController::class, 'tableParts'])->name('table-parts');
                if ($authEnabled) {
                    $tablePartsRoute->can(BasePolicy::VIEW_ANY, Article::class);
                }

                Route::prefix('categories')->name('categories.')->group(static function () use ($authEnabled): void {
                    $storeRoute = Route::post('/', [ArticleCategoriesController::class, 'store'])->name('store');
                    if ($authEnabled) {
                        $storeRoute->can(BasePolicy::CREATE, ArticleCategory::class);
                    }

                    $updateRoute = Route::put('/{articleCategory}', [ArticleCategoriesController::class, 'update'])->name('update');
                    if ($authEnabled) {
                        $updateRoute->can(BasePolicy::EDIT, ArticleCategory::class);
                    }

                    $destroyRoute = Route::delete('/{articleCategory}', [ArticleCategoriesController::class, 'destroy'])->name('destroy');
                    if ($authEnabled) {
                        $destroyRoute->can(BasePolicy::DELETE, ArticleCategory::class);
                    }

                    $tablePartsRoute = Route::get('/table-parts', [ArticleCategoriesController::class, 'tableParts'])->name('table-parts');
                    if ($authEnabled) {
                        $tablePartsRoute->can(BasePolicy::VIEW_ANY, ArticleCategory::class);
                    }
                });

                $updateRoute = Route::put('/{article}', [ArticlesController::class, 'update'])->name('update');
                if ($authEnabled) {
                    $updateRoute->can(BasePolicy::EDIT, Article::class);
                }

                $destroyRoute = Route::delete('/{article}', [ArticlesController::class, 'destroy'])->name('destroy');
                if ($authEnabled) {
                    $destroyRoute->can(BasePolicy::DELETE, Article::class);
                }
            });
        }

        if ($articlesEnabled) {
            Route::prefix('authors')->name('authors.')->group(static function () use ($authEnabled): void {
                $storeRoute = Route::post('/', [AuthorsController::class, 'store'])->name('store');
                if ($authEnabled) {
                    $storeRoute->can(BasePolicy::CREATE, Author::class);
                }

                $updateRoute = Route::put('/{author}', [AuthorsController::class, 'update'])->name('update');
                if ($authEnabled) {
                    $updateRoute->can(BasePolicy::EDIT, Author::class);
                }

                $destroyRoute = Route::delete('/{author}', [AuthorsController::class, 'destroy'])->name('destroy');
                if ($authEnabled) {
                    $destroyRoute->can(BasePolicy::DELETE, Author::class);
                }

                $tablePartsRoute = Route::get('/table-parts', [AuthorsController::class, 'tableParts'])->name('table-parts');
                if ($authEnabled) {
                    $tablePartsRoute->can(BasePolicy::VIEW_ANY, Author::class);
                }
            });
        }
    });
