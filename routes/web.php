<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;
use Patrikjak\Starter\Http\Controllers\Articles\ArticleCategoriesController;
use Patrikjak\Starter\Http\Controllers\Articles\ArticlesController;
use Patrikjak\Starter\Http\Controllers\Authors\AuthorsController;
use Patrikjak\Starter\Http\Controllers\DashboardController;
use Patrikjak\Starter\Http\Controllers\Metadata\MetadataController;
use Patrikjak\Starter\Http\Controllers\Profile\ProfileController;
use Patrikjak\Starter\Http\Controllers\StaticPages\StaticPagesController;
use Patrikjak\Starter\Http\Controllers\Users\PermissionsController;
use Patrikjak\Starter\Http\Controllers\Users\RolesController;
use Patrikjak\Starter\Http\Controllers\Users\UsersController;
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
$dashboardEnabled = config('pjstarter.features.dashboard');
$profileEnabled = config('pjstarter.features.profile');
$staticPagesEnabled = config('pjstarter.features.static_pages');
$articlesEnabled = config('pjstarter.features.articles');
$usersEnabled = config('pjstarter.features.users');

Route::prefix('admin')
    ->name('admin.')
    ->group(static function (): void {
        $authEnabled = config('pjstarter.features.auth');
        $middleware = $authEnabled ? ['web', 'auth'] : ['web'];
        $dashboardEnabled = config('pjstarter.features.dashboard');
        $profileEnabled = config('pjstarter.features.profile');
        $staticPagesEnabled = config('pjstarter.features.static_pages');
        $articlesEnabled = config('pjstarter.features.articles');
        $usersEnabled = config('pjstarter.features.users');

        if ($dashboardEnabled) {
            Route::middleware($middleware)->group(static function (): void {
                Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            });
        }

        if ($profileEnabled && $authEnabled) {
            Route::middleware($middleware)->group(static function (): void {
                Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
                Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
            });
        }

        if ($staticPagesEnabled) {
            Route::middleware($middleware)
                ->prefix('static-pages')
                ->name('static-pages.')
                ->group(static function () use ($authEnabled): void {
                    $indexRoute = Route::get('/', [StaticPagesController::class, 'index'])->name('index');
                    if ($authEnabled) {
                        $indexRoute->can(BasePolicy::VIEW_ANY, StaticPage::class);
                    }

                    $createRoute = Route::get('/create', [StaticPagesController::class, 'create'])->name('create');
                    if ($authEnabled) {
                        $createRoute->can(BasePolicy::CREATE, StaticPage::class);
                    }

                    $editRoute = Route::get('/{staticPage}/edit', [StaticPagesController::class, 'edit'])->name('edit');
                    if ($authEnabled) {
                        $editRoute->can(BasePolicy::EDIT, StaticPage::class);
                    }
                });
        }

        if ($articlesEnabled) {
            Route::middleware($middleware)
                ->prefix('articles')
                ->name('articles.')
                ->group(static function () use ($authEnabled): void {
                    $indexRoute = Route::get('/', [ArticlesController::class, 'index'])->name('index');
                    if ($authEnabled) {
                        $indexRoute->can(BasePolicy::VIEW_ANY, Article::class);
                    }

                    $createRoute = Route::get('/create', [ArticlesController::class, 'create'])->name('create');
                    if ($authEnabled) {
                        $createRoute->can(BasePolicy::CREATE, Article::class);
                    }

                    Route::prefix('categories')
                        ->name('categories.')
                        ->group(static function () use ($authEnabled): void {
                            $indexRoute = Route::get('/', [ArticleCategoriesController::class, 'index'])->name('index');
                            if ($authEnabled) {
                                $indexRoute->can(BasePolicy::VIEW_ANY, ArticleCategory::class);
                            }

                            $createRoute = Route::get('/create', [ArticleCategoriesController::class, 'create'])
                                ->name('create');
                            if ($authEnabled) {
                                $createRoute->can(BasePolicy::CREATE, ArticleCategory::class);
                            }

                            $editRoute = Route::get(
                                '/{articleCategory}/edit',
                                [ArticleCategoriesController::class, 'edit'],
                            )->name('edit');
                            if ($authEnabled) {
                                $editRoute->can(BasePolicy::EDIT, ArticleCategory::class);
                            }

                            $showRoute = Route::get('/{articleCategory}', [ArticleCategoriesController::class, 'show'])
                                ->name('show');
                            if ($authEnabled) {
                                $showRoute->can(BasePolicy::VIEW, ArticleCategory::class);
                            }
                        });

                    $editRoute = Route::get('/{article}/edit', [ArticlesController::class, 'edit'])->name('edit');
                    if ($authEnabled) {
                        $editRoute->can(BasePolicy::EDIT, Article::class);
                    }

                    $showRoute = Route::get('/{article}', [ArticlesController::class, 'show'])->name('show');
                    if ($authEnabled) {
                        $showRoute->can(BasePolicy::VIEW, Article::class);
                    }
                });
        }

        if ($articlesEnabled) {
            Route::middleware($middleware)
                ->prefix('authors')
                ->name('authors.')
                ->group(static function () use ($authEnabled): void {
                    $indexRoute = Route::get('/', [AuthorsController::class, 'index'])->name('index');
                    if ($authEnabled) {
                        $indexRoute->can(BasePolicy::VIEW_ANY, Author::class);
                    }

                    $createRoute = Route::get('/create', [AuthorsController::class, 'create'])->name('create');
                    if ($authEnabled) {
                        $createRoute->can(BasePolicy::CREATE, Author::class);
                    }

                    $showRoute = Route::get('/{author}', [AuthorsController::class, 'show'])->name('show');
                    if ($authEnabled) {
                        $showRoute->can(BasePolicy::VIEW, Author::class);
                    }

                    $editRoute = Route::get('/{author}/edit', [AuthorsController::class, 'edit'])->name('edit');
                    if ($authEnabled) {
                        $editRoute->can(BasePolicy::EDIT, Author::class);
                    }
                });
        }

        if ($staticPagesEnabled || $articlesEnabled) {
            Route::middleware($middleware)
                ->prefix('metadata')
                ->name('metadata.')
                ->group(static function () use ($authEnabled): void {
                    $indexRoute = Route::get('/', [MetadataController::class, 'index'])->name('index');
                    if ($authEnabled) {
                        $indexRoute->can(BasePolicy::VIEW_ANY, Metadata::class);
                    }

                    $showRoute = Route::get('/{metadata}', [MetadataController::class, 'show'])->name('show');
                    if ($authEnabled) {
                        $showRoute->can(BasePolicy::VIEW, Metadata::class);
                    }

                    $editRoute = Route::get('/{metadata}/edit', [MetadataController::class, 'edit'])->name('edit');
                    if ($authEnabled) {
                        $editRoute->can(BasePolicy::EDIT, Metadata::class);
                    }
                });
        }

        if ($usersEnabled) {
            Route::middleware($middleware)
                ->prefix('users')
                ->name('users.')
                ->group(static function () use ($authEnabled): void {
                    $indexRoute = Route::get('/', [UsersController::class, 'index'])->name('index');
                    if ($authEnabled) {
                        $indexRoute->can(BasePolicy::VIEW_ANY, User::class);
                    }

                    Route::prefix('roles')->name('roles.')->group(static function () use ($authEnabled): void {
                        $indexRoute = Route::get('/', [RolesController::class, 'index'])->name('index');
                        if ($authEnabled) {
                            $indexRoute->can(BasePolicy::VIEW_ANY, Role::class);
                        }

                        $showRoute = Route::get('/{role}', [RolesController::class, 'show'])->name('show');
                        if ($authEnabled) {
                            $showRoute->can(BasePolicy::VIEW, 'role');
                        }

                        $permissionsRoute = Route::get('/{role}/permissions', [RolesController::class, 'permissions'])
                            ->name('permissions');
                        if ($authEnabled) {
                            $permissionsRoute->can(RolePolicy::MANAGE, 'role');
                        }
                    });

                    Route::prefix('permissions')
                        ->name('permissions.')
                        ->group(static function () use ($authEnabled): void {
                        $indexRoute = Route::get('/', [PermissionsController::class, 'index'])->name('index');
                        if ($authEnabled) {
                            $indexRoute->can(BasePolicy::VIEW_ANY, Permission::class);
                        }
                    });
                });
        }
    });