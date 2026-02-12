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
        $response = $this->get(route('admin.dashboard'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
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
        $response = $this->get(route('admin.static-pages.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testArticlesAccessibleWithoutAuth(): void
    {
        $response = $this->get(route('admin.articles.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testAuthorsAccessibleWithoutAuth(): void
    {
        $response = $this->get(route('admin.authors.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testArticleCategoriesAccessibleWithoutAuth(): void
    {
        $response = $this->get(route('admin.articles.categories.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testMetadataAccessibleWithoutAuth(): void
    {
        $response = $this->get(route('admin.metadata.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testUsersAccessibleWithoutAuth(): void
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testRolesAccessibleWithoutAuth(): void
    {
        $response = $this->get(route('admin.users.roles.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testPermissionsAccessibleWithoutAuth(): void
    {
        $response = $this->get(route('admin.users.permissions.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}
