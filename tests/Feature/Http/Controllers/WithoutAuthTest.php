<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class WithoutAuthTest extends TestCase
{
    #[DefineEnvironment('disableAuth')]
    public function testDashboardAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.dashboard'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    public function testLoginReturns404WhenAuthDisabled(): void
    {
        $this->get('/login')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testRegisterReturns404WhenAuthDisabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testApiLoginReturns404WhenAuthDisabled(): void
    {
        $this->postJson('/api/login')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testApiLogoutReturns404WhenAuthDisabled(): void
    {
        $this->postJson('/api/logout')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testProfileReturns404WhenAuthDisabled(): void
    {
        $this->get('/admin/profile')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testChangePasswordReturns404WhenAuthDisabled(): void
    {
        $this->get('/admin/change-password')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testStaticPagesAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.static-pages.index'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testArticlesAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.articles.index'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testAuthorsAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.authors.index'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testArticleCategoriesAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.articles.categories.index'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testMetadataAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.metadata.index'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testUsersAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.users.index'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testRolesAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.users.roles.index'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testPermissionsAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.users.permissions.index'))->assertOk();
    }
}
