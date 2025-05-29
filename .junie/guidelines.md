# Development Guidelines for Patrikjak Starter

This document provides essential information for developers working on the Patrikjak Starter project.

## Build/Configuration Instructions

### PHP Setup

- PHP 8.4 is required
- Composer is used for dependency management
- Docker is available for development environment

### Docker Setup

The project includes a Docker configuration for development:

```bash
# Start the Docker environment
docker compose up -d

# Run commands in the container
docker compose run --rm cli <command>
```

The Docker setup includes:
- PHP 8.4 CLI image with Xdebug enabled
- Project directory mounted to `/var/www` in the container

### Frontend Build

The project uses Vite for frontend asset building:

```bash
# Install dependencies
npm install

# Development build with hot reloading
npm run dev

# Production build
npm run build
```

## Testing Information

### Test Configuration

- PHPUnit is used for testing
- Tests are organized into Feature and Unit tests
- Orchestra Testbench is used for package testing
- Snapshot testing is available via Spatie's PHPUnit Snapshot Assertions

### Running Tests

```bash
# Run all tests
docker compose run --rm cli vendor/bin/phpunit

# Run a specific test file
docker compose run --rm cli vendor/bin/phpunit tests/Unit/ExampleTest.php

# Run tests with a specific filter
docker compose run --rm cli vendor/bin/phpunit --filter testExample
```

### Creating Tests

1. Tests should extend `Patrikjak\Starter\Tests\TestCase`
2. Feature tests go in `tests/Feature/`, unit tests in `tests/Unit/`
3. API tests should be in the API namespace (e.g., `Patrikjak\Starter\Tests\Feature\Http\Controllers\Api`)
4. Test files should be named with a `Test` suffix (e.g., `ExampleTest.php`)
5. Test methods should be prefixed with `test` (e.g., `testExample`)
6. When using `assertJsonValidationErrors`, also add `assertMatchesJsonSnapshot` with content to capture the full response

#### Example Unit Test

```php
<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit;

use Patrikjak\Starter\Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testExample(): void
    {
        $this->assertTrue(true);
    }
}
```

### Test Factories

The project includes factory classes for creating test data:

- `UserFactory` provides methods to create different types of users (SuperAdmin, Admin, regular User)
- The `WithTestUser` trait provides convenient methods for creating and authenticating as different user types

Example usage:

```php
// Create and authenticate as a super admin
$user = $this->createAndActAsSuperAdmin();

// Create and authenticate as an admin with specific permissions
$user = $this->createAndActAsAdmin(['manage-role']);

// Create and authenticate as a regular user
$user = $this->createAndActAsUser();
```

### Assertions for Factory-Created Instances

When using factories to create model instances in tests, always add assertions to verify the type of the created instance. This ensures type safety and helps catch potential issues with factory configurations.

Example:

```php
// Create a model instance using a factory
$category = ArticleCategory::factory()->create();
$author = Author::factory()->create();

// Add assertions to verify the instance types
assert($category instanceof ArticleCategory);
assert($author instanceof Author);

// Now use the instances in your test
$response = $this->postJson(route('admin.api.articles.store'), [
    'title' => 'Test Article',
    'category' => $category->id,
    'author' => $author->id,
    // ...
]);
```

These assertions should be added immediately after creating the instances and before using them in the test. This practice helps with:

1. Type safety - Ensures the factory created the expected type of object
2. IDE support - Helps IDEs provide better code completion and type hinting
3. Static analysis - Improves PHPStan analysis by confirming types
4. Documentation - Makes the expected types clear to other developers

## Code Style and Development Practices

### Static Analysis

The project uses PHPStan (with Larastan) for static analysis:

```bash
# Run PHPStan
docker-compose run --rm cli vendor/bin/phpstan analyse
```

Configuration:
- Level 6 strictness
- Analyzes both `src/` and `tests/` directories
- Baseline file for ignoring existing errors

### Coding Standards

The project uses Slevomat Coding Standard for PHP code style:

- Strict type declarations are required
- Classes should not exceed 300 lines
- Constructor property promotion is required
- Strict equality operators (`===` and `!==`) are required
- Type hints are required for parameters, properties, and return values

Key rules:
- Arrays: trailing commas, consistent whitespace
- Classes: structured organization, visibility for constants
- Namespaces: alphabetically sorted use statements
- Functions: no empty functions, static closures when possible
- Don't comment code blocks - remove unused code instead of commenting it out

### Development Workflow

1. Write tests for new functionality
2. Implement the functionality
3. Run tests to verify the implementation
4. Run static analysis to check for issues
5. Fix any code style issues
6. Submit changes

## Permissions System

### Permission Structure

- Permissions are defined in the `PermissionsDefinition.php` file
- Each permission consists of a feature and an action:
  - Feature is defined in policy classes (e.g., `MetadataPolicy::FEATURE_NAME`)
  - Action is defined in `BasePolicy` (e.g., `BasePolicy::EDIT`)
- Permissions are used in the format `action-feature`, for example:
  - `edit-metadata` (combines the action "edit" with the feature "metadata")

## Additional Resources

- The project includes a comprehensive test suite that can serve as examples
- Snapshot testing is used for testing HTML responses
- The `TestCase` class provides useful utilities for testing
