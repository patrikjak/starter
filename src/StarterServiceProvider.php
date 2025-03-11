<?php

declare(strict_types = 1);

namespace Patrikjak\Starter;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Patrikjak\Starter\Console\Commands\InstallCommand;

class StarterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerComponents();

        $this->publishAssets();
        $this->publishViews();
        $this->publishConfig();
        $this->publishTranslations();

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

    private function publishViews(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/pjstarter'),
        ], 'pjstarter-views');
    }

    public function publishTranslations(): void
    {
        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/pjstarter'),
        ], 'pjstarter-translations');
    }

    private function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
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