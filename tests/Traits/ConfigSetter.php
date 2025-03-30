<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Traits;

use Illuminate\Contracts\Foundation\Application;

trait ConfigSetter
{
    protected function enableDashboard(Application $app): void
    {
        $app['config']->set('pjstarter.features.dashboard', true);
    }

    protected function disableDashboard(Application $app): void
    {
        $app['config']->set('pjstarter.features.dashboard', false);
    }

    protected function enableProfile(Application $app): void
    {
        $app['config']->set('pjstarter.features.profile', true);
    }

    protected function disableProfile(Application $app): void
    {
        $app['config']->set('pjstarter.features.profile', false);
    }

    protected function enableStaticPages(Application $app): void
    {
        $app['config']->set('pjstarter.features.static_pages', true);
    }

    protected function disableStaticPages(Application $app): void
    {
        $app['config']->set('pjstarter.features.static_pages', false);
    }

    protected function enableAllFeatures(Application $app): void
    {
        $app['config']->set('pjstarter.features', [
            'dashboard' => true,
            'profile' => true,
            'static_pages' => true,
        ]);
    }

    protected function disableAllFeatures(Application $app): void
    {
        $app['config']->set('pjstarter.features', [
            'dashboard' => false,
            'profile' => false,
            'static_pages' => false,
        ]);
    }
} 