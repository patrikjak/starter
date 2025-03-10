<?php

namespace Patrikjak\Starter;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class StarterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerComponents();

        $this->publishAssets();

        $this->loadRoutes();
        $this->loadViews();
        $this->loadTranslations();

        $this->publishConfig();
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