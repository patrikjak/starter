# Testing

## Structure

- Extend `Patrikjak\Starter\Tests\TestCase`
- Feature tests in `tests/Feature/`, unit tests in `tests/Unit/`
- Test methods prefixed with `test` (e.g., `testPageCanBeRendered`)
- Organized by controller and action: `tests/Feature/Http/Controllers/{ControllerName}/{ActionTest}.php`
  - e.g., `StaticPagesController/IndexTest.php`, `ArticlesController/CreateTest.php`
- API tests follow the same pattern under `Api/` subdirectory

## Test Utilities

```php
// Create authenticated users
$this->createAndActAsSuperAdmin();
$this->createAndActAsAdmin(['edit-article', 'delete-article']);
$this->createAndActAsUser();

// Feature toggles via DefineEnvironment attribute
#[DefineEnvironment('enableArticles')]
#[DefineEnvironment('disableAuth')]

// Factory assertions (required for type safety)
$category = ArticleCategory::factory()->create();
assert($category instanceof ArticleCategory);
```

## ConfigSetter Trait Methods

Available for `#[DefineEnvironment()]`:

- `enableAuth` / `disableAuth`
- `enableDashboard` / `disableDashboard`
- `enableProfile` / `disableProfile`
- `enableStaticPages` / `disableStaticPages`
- `enableArticles` / `disableArticles`
- `enableUsers` / `disableUsers`
- `enableAllFeatures` / `disableAllFeatures`

## Snapshot Testing

- Use `assertMatchesHtmlSnapshot()` for HTML responses
- Use `assertMatchesJsonSnapshot()` for JSON responses
- Snapshots stored in `__snapshots__/` next to test files
- Update snapshots: `docker compose run --rm cli vendor/bin/phpunit -d --update-snapshots`

## Example Test

```php
<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\StaticPagesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

final class IndexTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->get(route('admin.static-pages.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}
```

## Commands

```bash
# Run all tests
docker compose run --rm cli vendor/bin/phpunit

# Run specific test file
docker compose run --rm cli vendor/bin/phpunit tests/Feature/Http/Controllers/StaticPagesController/IndexTest.php

# Filter by method name
docker compose run --rm cli vendor/bin/phpunit --filter testPageCanBeRendered

# Update snapshots
docker compose run --rm cli vendor/bin/phpunit -d --update-snapshots
```
