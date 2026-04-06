<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests;

use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Tests\Traits\ConfigSetter;
use Patrikjak\Starter\Tests\Traits\WithTestUser;
use Spatie\Snapshots\MatchesSnapshots;

use function Orchestra\Testbench\package_path;

abstract class TestCase extends BaseTestCase
{
    use WithTestUser;
    use ConfigSetter;
    use MatchesSnapshots {
        MatchesSnapshots::assertMatchesHtmlSnapshot as baseAssertMatchesHtmlSnapshot;
    }
    use RefreshDatabase;

    protected const string TESTER_NAME = 'Tester';

    public function assertMatchesHtmlSnapshot(string $actual): void
    {
        $actual = preg_replace(
            '/<meta\s+name="csrf-token"\s+content="([^"]+)"/',
            '<meta name="csrf-token" content="{CSRF-TOKEN}"',
            $actual
        );

        $actual = preg_replace('/name="_token"\s+value="[^"]*"/', 'name="_token" value="{TOKEN}"', $actual);

        $actual = preg_replace(
            '/<input[^>]*name="token"\s*["\']?[^>]*value\s*=\s*["\']?([^"\'\s>]+)["\']?>/',
            '<input name="token" value="TOKEN"> <!-- REPLACED INPUT -->',
            $actual,
        );

        $this->baseAssertMatchesHtmlSnapshot($actual);
    }

    public function copyIconsToTestSkeleton(): void
    {
        exec(sprintf('cp -r %s %s', package_path('resources/views/icons'), resource_path('views/icons')));
    }

    public function deleteIconsFromTestSkeleton(): void
    {
        exec('rm -rf ' . resource_path('views/icons'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->setLocale('test');
        $this->app->setFallbackLocale('test');

        Carbon::setTestNow(Carbon::create(2025, 5, 18));

        $this->seedRoles();
        $this->artisan('pjstarter:permissions:sync');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(Carbon::now());

        parent::tearDown();
    }

    private function seedRoles(): void
    {
        Role::insert([
            [
                'id' => '00000000-0000-0000-0000-000000000001',
                'slug' => 'superadmin',
                'name' => 'Superadmin',
                'is_superadmin' => true,
            ],
            [
                'id' => '00000000-0000-0000-0000-000000000002',
                'slug' => 'admin',
                'name' => 'Admin',
                'is_superadmin' => false,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array
    {
        return [
            'Patrikjak\Starter\StarterServiceProvider',
            'Patrikjak\Auth\AuthServiceProvider',
            'Patrikjak\Utils\UtilsServiceProvider',
            'BladeUI\Icons\BladeIconsServiceProvider',
            'BladeUI\Heroicons\BladeHeroiconsServiceProvider',
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/patrikjak/auth/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/features/static-pages');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/features/slugs');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/features/metadata');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/features/users');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/features/authors');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/features/articles');
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @param Application $app
     */
    protected function defineEnvironment($app): void
    {
        tap($app['config'], static function (Repository $config): void {
            $config->set('auth.providers.users.model', User::class);
            $config->set('pjauth.models.role', Role::class);
        });
    }
}
