<?php

declare(strict_types = 1);

namespace Patrikjak\Starter;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Patrikjak\Starter\Console\Commands\InstallCommand;
use Patrikjak\Starter\Console\Commands\SyncPermissionsCommand;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\Articles\ArticleCategoryPolicy;
use Patrikjak\Starter\Policies\Authors\AuthorPolicy;
use Patrikjak\Starter\Policies\Metadata\MetadataPolicy;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;
use Patrikjak\Starter\Policies\Users\PermissionPolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;
use Patrikjak\Starter\Policies\Users\UserPolicy;
use Patrikjak\Starter\Repositories\Articles\ArticleCategoryRepository;
use Patrikjak\Starter\Repositories\Authors\AuthorRepository;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository as ArticleCategoryRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository as AuthorRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Metadata\MetadataRepository as MetadataRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Slugs\SlugRepository as SlugRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\StaticPages\StaticPageRepository as StaticPageRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository as PermissionRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository as RoleRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\Users\UserRepository as UserRepositoryContract;
use Patrikjak\Starter\Repositories\Metadata\MetadataRepository;
use Patrikjak\Starter\Repositories\Slugs\SlugRepository;
use Patrikjak\Starter\Repositories\StaticPages\StaticPageRepository;
use Patrikjak\Starter\Repositories\Users\PermissionRepository;
use Patrikjak\Starter\Repositories\Users\RoleRepository;
use Patrikjak\Starter\Repositories\Users\UserRepository;

class StarterServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public array $bindings = [
        SlugRepositoryContract::class => SlugRepository::class,
        StaticPageRepositoryContract::class => StaticPageRepository::class,
        MetadataRepositoryContract::class => MetadataRepository::class,
        UserRepositoryContract::class => UserRepository::class,
        RoleRepositoryContract::class => RoleRepository::class,
        PermissionRepositoryContract::class => PermissionRepository::class,
        AuthorRepositoryContract::class => AuthorRepository::class,
        ArticleCategoryRepositoryContract::class => ArticleCategoryRepository::class,
    ];

    public function boot(): void
    {
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
        Gate::policy(StaticPage::class, StaticPagePolicy::class);
        Gate::policy(Metadata::class, MetadataPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Author::class, AuthorPolicy::class);
        Gate::policy(ArticleCategory::class, ArticleCategoryPolicy::class);
    }
}