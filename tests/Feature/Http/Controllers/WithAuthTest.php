<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class WithAuthTest extends TestCase
{
    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testStaticPagesRequiresAuthenticationWhenEnabled(): void
    {
        $this->get(route('admin.static-pages.index'))->assertRedirect('/login');
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testStaticPagesRequiresPermissionsWhenAuthenticated(): void
    {
        $this->createAndActAsUser(); // User without permissions

        $this->get(route('admin.static-pages.index'))->assertForbidden();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testStaticPagesAccessibleWithCorrectPermissions(): void
    {
        $this->createAndActAsAdmin(['viewAny-static_page']);

        $this->get(route('admin.static-pages.index'))->assertOk();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testArticlesRequiresAuthenticationWhenEnabled(): void
    {
        $this->get(route('admin.articles.index'))->assertRedirect('/login');
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testArticlesRequiresPermissionsWhenAuthenticated(): void
    {
        $this->createAndActAsUser();

        $this->get(route('admin.articles.index'))->assertForbidden();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testArticlesAccessibleWithCorrectPermissions(): void
    {
        $this->createAndActAsAdmin(['viewAny-article']);

        $this->get(route('admin.articles.index'))->assertOk();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testAuthorsRequiresAuthenticationWhenEnabled(): void
    {
        $this->get(route('admin.authors.index'))->assertRedirect('/login');
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testAuthorsRequiresPermissionsWhenAuthenticated(): void
    {
        $this->createAndActAsUser();

        $this->get(route('admin.authors.index'))->assertForbidden();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableArticles')]
    public function testAuthorsAccessibleWithCorrectPermissions(): void
    {
        $this->createAndActAsAdmin(['viewAny-author']);

        $this->get(route('admin.authors.index'))->assertOk();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testUsersRequiresAuthenticationWhenEnabled(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect('/login');
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testUsersRequiresPermissionsWhenAuthenticated(): void
    {
        $this->createAndActAsUser();

        $this->get(route('admin.users.index'))->assertForbidden();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableUsers')]
    public function testUsersAccessibleWithCorrectPermissions(): void
    {
        $this->createAndActAsAdmin(['viewAny-user']);

        $this->get(route('admin.users.index'))->assertOk();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testMetadataRequiresAuthenticationWhenEnabled(): void
    {
        $this->get(route('admin.metadata.index'))->assertRedirect('/login');
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testMetadataRequiresPermissionsWhenAuthenticated(): void
    {
        $this->createAndActAsUser();

        $this->get(route('admin.metadata.index'))->assertForbidden();
    }

    #[DefineEnvironment('enableAuth')]
    #[DefineEnvironment('enableStaticPages')]
    public function testMetadataAccessibleWithCorrectPermissions(): void
    {
        $this->createAndActAsAdmin(['viewAny-metadata']);

        $this->get(route('admin.metadata.index'))->assertOk();
    }
}
