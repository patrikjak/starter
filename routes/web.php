<?php

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
    ->group(static function () {
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
                ->group(static function (): void {
                    Route::get('/', [StaticPagesController::class, 'index'])
                        ->name('index')
                        ->can(BasePolicy::VIEW_ANY, StaticPage::class);

                    Route::get('/create', [StaticPagesController::class, 'create'])
                        ->name('create')
                        ->can(BasePolicy::CREATE, StaticPage::class);

                    Route::get('/{staticPage}/edit', [StaticPagesController::class, 'edit'])
                        ->name('edit')
                        ->can(BasePolicy::EDIT, StaticPage::class);
                });
        }

        if ($articlesEnabled) {
            Route::middleware($middleware)
                ->prefix('articles')
                ->name('articles.')
                ->group(static function (): void {
                    Route::get('/', [ArticlesController::class, 'index'])
                        ->name('index')
                        ->can(BasePolicy::VIEW_ANY, Article::class);

                    Route::get('/create', [ArticlesController::class, 'create'])
                        ->name('create')
                        ->can(BasePolicy::CREATE, Article::class);

                    Route::prefix('categories')
                        ->name('categories.')
                        ->group(static function (): void {
                            Route::get('/', [ArticleCategoriesController::class, 'index'])
                                ->name('index')
                                ->can(BasePolicy::VIEW_ANY, ArticleCategory::class);

                            Route::get('/create', [ArticleCategoriesController::class, 'create'])
                                ->name('create')
                                ->can(BasePolicy::CREATE, ArticleCategory::class);

                            Route::get('/{articleCategory}/edit', [ArticleCategoriesController::class, 'edit'])
                                ->name('edit')
                                ->can(BasePolicy::EDIT, ArticleCategory::class);

                            Route::get('/{articleCategory}', [ArticleCategoriesController::class, 'show'])
                                ->name('show')
                                ->can(BasePolicy::VIEW, ArticleCategory::class);
                        });

                    Route::get('/{article}/edit', [ArticlesController::class, 'edit'])
                        ->name('edit')
                        ->can(BasePolicy::EDIT, Article::class);

                    Route::get('/{article}', [ArticlesController::class, 'show'])
                        ->name('show')
                        ->can(BasePolicy::VIEW, Article::class);
                });
        }

        if ($articlesEnabled) {
            Route::middleware($middleware)
                ->prefix('authors')
                ->name('authors.')
                ->group(static function (): void {
                    Route::get('/', [AuthorsController::class, 'index'])
                        ->name('index')
                        ->can(BasePolicy::VIEW_ANY, Author::class);

                    Route::get('/create', [AuthorsController::class, 'create'])
                        ->name('create')
                        ->can(BasePolicy::CREATE, Author::class);

                    Route::get('/{author}', [AuthorsController::class, 'show'])
                        ->name('show')
                        ->can(BasePolicy::VIEW, Author::class);

                    Route::get('/{author}/edit', [AuthorsController::class, 'edit'])
                        ->name('edit')
                        ->can(BasePolicy::EDIT, Author::class);
                });
        }

        if ($staticPagesEnabled || $articlesEnabled) {
            Route::middleware($middleware)
                ->prefix('metadata')
                ->name('metadata.')
                ->group(static function (): void {
                    Route::get('/', [MetadataController::class, 'index'])
                        ->name('index')
                        ->can(BasePolicy::VIEW_ANY, Metadata::class);

                    Route::get('/{metadata}', [MetadataController::class, 'show'])->name('show')
                        ->can(BasePolicy::VIEW, Metadata::class);

                    Route::get('/{metadata}/edit', [MetadataController::class, 'edit'])
                        ->name('edit')
                        ->can(BasePolicy::EDIT, Metadata::class);
                });
        }

        if ($usersEnabled) {
            Route::middleware($middleware)
                ->prefix('users')
                ->name('users.')
                ->group(static function (): void {
                    Route::get('/', [UsersController::class, 'index'])
                        ->name('index')
                        ->can(BasePolicy::VIEW_ANY, User::class);

                    Route::prefix('roles')->name('roles.')->group(static function (): void {
                        Route::get('/', [RolesController::class, 'index'])
                            ->name('index')
                            ->can(BasePolicy::VIEW_ANY, Role::class);

                        Route::get('/{role}', [RolesController::class, 'show'])
                            ->name('show')
                            ->can(BasePolicy::VIEW, 'role');

                        Route::get('/{role}/permissions', [RolesController::class, 'permissions'])
                            ->name('permissions')
                            ->can(RolePolicy::MANAGE, 'role');
                    });

                    Route::prefix('permissions')->name('permissions.')->group(static function (): void {
                        Route::get('/', [PermissionsController::class, 'index'])
                            ->name('index')
                            ->can(BasePolicy::VIEW_ANY, Permission::class);
                    });
                });
        }
    });