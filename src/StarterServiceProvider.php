<?php

declare(strict_types = 1);

namespace Patrikjak\Starter;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Patrikjak\Starter\Console\Commands\InstallCommand;
use Patrikjak\Starter\Repositories\Contracts\PageSlugRepository as PageRepositoryContract;
use Patrikjak\Starter\Repositories\Contracts\StaticPages\StaticPageRepository as StaticPageRepositoryContract;
use Patrikjak\Starter\Repositories\PageSlugRepository;
use Patrikjak\Starter\Repositories\StaticPages\StaticPageRepository;

class StarterServiceProvider extends ServiceProvider
{
    public array $bindings = [
        PageRepositoryContract::class => PageSlugRepository::class,
        StaticPageRepositoryContract::class => StaticPageRepository::class,
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
        /*if (config('pjstarter.features.metadata')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/metadata' => database_path('migrations'),
            ], 'pjstarter-migrations');
        }*/

        if (config('pjstarter.features.static_pages')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/static-pages' => database_path('migrations'),
            ], 'pjstarter-migrations');
        }

        if (config('pjstarter.features.metadata') || config('pjstarter.features.static_pages')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/features/page-slugs' => database_path('migrations'),
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
}