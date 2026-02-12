<?php

declare(strict_types = 1);

namespace Patrikjak\Starter;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Patrikjak\Starter\Console\Commands\InstallCommand;
use Patrikjak\Starter\Console\Commands\SyncPermissionsCommand;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Slugs\Sluggable;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\Articles\ArticleCategoryPolicy;
use Patrikjak\Starter\Policies\Articles\ArticlePolicy;
use Patrikjak\Starter\Policies\Authors\AuthorPolicy;
use Patrikjak\Starter\Policies\Metadata\MetadataPolicy;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;
use Patrikjak\Starter\Policies\Users\PermissionPolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;
use Patrikjak\Starter\Policies\Users\UserPolicy;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository as ArticleCategoryRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleRepository as ArticleRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository as AuthorRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Metadata\MetadataRepository as MetadataRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Slugs\SlugRepository as SlugRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\StaticPages\StaticPageRepository as StaticPageRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository as PermissionRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository as RoleRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Users\UserRepository as UserRepositoryContract;
use Patrikjak\Starter\Repositories\Eloquent\Articles\EloquentArticleCategoryRepository;
use Patrikjak\Starter\Repositories\Eloquent\Articles\EloquentArticleRepository;
use Patrikjak\Starter\Repositories\Eloquent\Authors\EloquentAuthorRepository;
use Patrikjak\Starter\Repositories\Eloquent\Metadata\EloquentMetadataRepository;
use Patrikjak\Starter\Repositories\Eloquent\Slugs\EloquentSlugRepository;
use Patrikjak\Starter\Repositories\Eloquent\StaticPages\EloquentStaticPageRepository;
use Patrikjak\Starter\Repositories\Eloquent\Users\EloquentPermissionRepository;
use Patrikjak\Starter\Repositories\Eloquent\Users\EloquentRoleRepository;
use Patrikjak\Starter\Repositories\Eloquent\Users\EloquentUserRepository;

class StarterServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public array $bindings = [
        SlugRepositoryContract::class => EloquentSlugRepository::class,
        StaticPageRepositoryContract::class => EloquentStaticPageRepository::class,
        MetadataRepositoryContract::class => EloquentMetadataRepository::class,
        UserRepositoryContract::class => EloquentUserRepository::class,
        RoleRepositoryContract::class => EloquentRoleRepository::class,
        PermissionRepositoryContract::class => EloquentPermissionRepository::class,
        AuthorRepositoryContract::class => EloquentAuthorRepository::class,
        ArticleCategoryRepositoryContract::class => EloquentArticleCategoryRepository::class,
        ArticleRepositoryContract::class => EloquentArticleRepository::class,
    ];

    public function boot(): void
    {
        $authEnabled = (bool) config('pjstarter.features.auth');

        if (!$authEnabled) {
            $this->disableAuthFeatures();
        }

        $this->registerComponents();

        $this->publishAssets();
        $this->publishViews();
        $this->publishConfig();
        $this->publishTranslations();
        $this->publishMigrations();

        $this->loadRoutes();
        $this->loadViews();
        $this->loadTranslations();
        $this->loadCommands();

        $this->loadExplicitRouteKeys();

        if (!$authEnabled) {
            $this->registerAuthFallbackRoutes();

            $this->app->booted(function (): void {
                $this->registerAuthFallbackRoutes();
            });
        }

        $this->registerPolicies();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pjstarter.php', 'pjstarter');
    }

    public function publishAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../public/images/icons' => public_path('images/icons'),
        ], 'pjstarter-assets');

        $this->publishes([
            __DIR__ . '/../resources/views/icons' => resource_path('views/icons'),
        ], 'pjstarter-assets');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/pjstarter'),
        ], 'pjstarter-assets');
    }

    public function publishTranslations(): void
    {
        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/pjstarter'),
        ], 'pjstarter-translations');
    }

    private function publishViews(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/pjstarter'),
        ], 'pjstarter-views');
    }

    private function publishMigrations(): void
    {
        if (config('pjstarter.features.static_pages')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/static-pages' => database_path('migrations'),
            ], 'pjstarter-migrations');
        }

        if (config('pjstarter.features.static_pages')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/slugs' => database_path('migrations'),
            ], 'pjstarter-migrations');

            $this->publishes([
                __DIR__ . '/../database/migrations/features/metadata' => database_path('migrations'),
            ], 'pjstarter-migrations');
        }

        if (config('pjstarter.features.users')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/users' => database_path('migrations'),
            ], 'pjstarter-migrations');
        }

        if (config('pjstarter.features.articles')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/articles' => database_path('migrations'),
            ], 'pjstarter-migrations');
        }

        if (config('pjstarter.features.articles')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/authors' => database_path('migrations'),
            ], 'pjstarter-migrations');
        }
    }

    private function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    private function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pjstarter');
    }

    private function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'pjstarter');
    }

    private function loadCommands(): void
    {
        $this->commands([
            InstallCommand::class,
            SyncPermissionsCommand::class,
        ]);
    }

    private function registerComponents(): void
    {
        Blade::componentNamespace('Patrikjak\\Starter\\View', 'pjstarter');
    }

    private function publishConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/pjstarter.php' => config_path('pjstarter.php'),
        ], 'pjstarter-config');
    }

    private function registerPolicies(): void
    {
        if (!config('pjstarter.features.auth')) {
            Gate::before(static fn (?User $user) => true);

            return;
        }

        Gate::policy(StaticPage::class, StaticPagePolicy::class);
        Gate::policy(Metadata::class, MetadataPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Author::class, AuthorPolicy::class);
        Gate::policy(Article::class, ArticlePolicy::class);
        Gate::policy(ArticleCategory::class, ArticleCategoryPolicy::class);
    }

    private function disableAuthFeatures(): void
    {
        config()->set('pjauth.features.login', false);
        config()->set('pjauth.features.register', false);
        config()->set('pjauth.features.password_reset', false);
        config()->set('pjauth.features.change_password', false);
        config()->set('pjauth.features.register_via_invitation', false);
    }

    private function registerAuthFallbackRoutes(): void
    {
        $abort404 = static fn () => abort(404);

        Route::middleware('web')->group(static function () use ($abort404): void {
            Route::any('/login', $abort404)->name('login');
            Route::any('/register', $abort404)->name('register');
            Route::any('/password/{any?}', $abort404)->where('any', '.*');
        });

        Route::middleware('web')->prefix('api')->group(static function () use ($abort404): void {
            Route::any('/login', $abort404);
            Route::any('/register', $abort404);
            Route::any('/logout', $abort404)->name('api.logout');
            Route::any('/password/{any?}', $abort404)->where('any', '.*');
        });
    }

    private function loadExplicitRouteKeys(): void
    {
        Route::bind('sluggable', function (string $value): Sluggable {
            $slugRepository = $this->app->make(SlugRepositoryContract::class);
            $sluggable = $slugRepository->getByUri($value);

            if ($sluggable === null) {
                abort(404);
            }

            return $sluggable->sluggable;
        });
    }
}