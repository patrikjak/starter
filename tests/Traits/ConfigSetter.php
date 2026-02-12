<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Traits;

use Illuminate\Contracts\Foundation\Application;

trait ConfigSetter
{
    protected function enableAuth(Application $app): void
    {
        $app['config']->set('pjstarter.features.auth', true);
    }

    protected function disableAuth(Application $app): void
    {
        $app['config']->set('pjstarter.features.auth', false);
    }

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

    protected function enableArticles(Application $app): void
    {
        $app['config']->set('pjstarter.features.articles', true);
    }

    protected function disableArticles(Application $app): void
    {
        $app['config']->set('pjstarter.features.articles', false);
    }

    protected function enableUsers(Application $app): void
    {
        $app['config']->set('pjstarter.features.users', true);
    }

    protected function disableUsers(Application $app): void
    {
        $app['config']->set('pjstarter.features.users', false);
    }

    protected function enableAllFeatures(Application $app): void
    {
        $app['config']->set('pjstarter.features', [
            'auth' => true,
            'dashboard' => true,
            'profile' => true,
            'static_pages' => true,
            'articles' => true,
            'users' => true,
        ]);
    }

    protected function disableAllFeatures(Application $app): void
    {
        $app['config']->set('pjstarter.features', [
            'auth' => false,
            'dashboard' => false,
            'profile' => false,
            'static_pages' => false,
            'articles' => false,
            'users' => false,
        ]);
    }
} 