# CLAUDE.md - AI Assistant Guidelines for Patrikjak Starter

## Project Overview

**Patrikjak Starter** is a Laravel package that provides a reusable admin panel scaffolding system. It includes built-in support for articles, authors, metadata, static pages, user management, roles, and permissions. Authentication can be fully disabled via configuration.

- **Type:** Laravel Library/Package
- **PHP Version:** 8.4 (required)
- **Laravel Version:** 12.x
- **License:** MIT
- **Dependencies:** `patrikjak/utils`, `patrikjak/auth`

## Codebase Structure

```
src/                          # Main source code
├── Casts/                    # Eloquent attribute casts
├── Console/Commands/         # Artisan commands
├── Dto/                      # Data Transfer Objects (Articles, Editorjs, Metadata, Slugs, Users)
├── Enums/                    # PHP 8.1+ Enums (ArticleStatus, Visibility, BlockType, etc.)
├── Exceptions/               # Custom exceptions
├── Factories/                # Model factories
├── Http/
│   ├── Controllers/          # Web & API controllers (organized by feature)
│   └── Requests/             # Form request validation classes
├── Models/                   # Eloquent models (organized by feature)
├── Observers/                # Eloquent observers (Metadata, Slugs)
├── Policies/                 # Authorization policies with BasePolicy
├── Repositories/
│   ├── Contracts/            # Repository interfaces
│   └── Eloquent/             # Eloquent implementations (EloquentXxxRepository)
├── Rules/                    # Custom validation rules
├── Services/                 # Business logic layer
├── Support/                  # Helper utilities
├── View/                     # Blade view components
└── StarterServiceProvider.php

tests/
├── Feature/
│   ├── Http/Controllers/     # Feature tests (organized by controller, then by action)
│   └── View/                 # View component tests
├── Unit/                     # Unit tests for services/renderers
├── Factories/                # Test-specific factories
├── Traits/                   # Test utilities (WithTestUser, ConfigSetter)
└── TestCase.php              # Base test class

database/
├── factories/                # Database factories (by feature)
└── migrations/features/      # Migrations organized by feature

resources/
├── views/                    # Blade templates
├── css/                      # SCSS and Tailwind styles
└── js/                       # TypeScript files

routes/
├── web.php                   # Admin panel routes (/admin prefix)
└── api.php                   # API routes

config/pjstarter.php          # Package configuration
lang/{en,sk}/                  # Translations
```

## Quick Commands

### Development (Docker-based)

```bash
# Start Docker environment
docker compose up -d

# Run tests
docker compose run --rm cli vendor/bin/phpunit

# Run specific test
docker compose run --rm cli vendor/bin/phpunit tests/Feature/Http/Controllers/StaticPagesController/IndexTest.php

# Run tests with filter
docker compose run --rm cli vendor/bin/phpunit --filter testPageCanBeRendered

# Check code style (PHPCS)
docker compose run --rm cli vendor/bin/phpcs --standard=ruleset.xml

# Fix code style (PHPCBF)
docker compose run --rm cli vendor/bin/phpcbf --standard=ruleset.xml

# Run static analysis (PHPStan - Level 6)
docker compose run --rm cli php -d memory_limit=2G vendor/bin/phpstan analyse

# Generate PHPStan baseline
docker compose run --rm cli php -d memory_limit=2G vendor/bin/phpstan analyse --generate-baseline

# Update test snapshots
docker compose run --rm cli vendor/bin/phpunit -d --update-snapshots
```

### Frontend

```bash
# Install dependencies
docker compose run --rm node npm install

# Development server with HMR
docker compose run --rm node npm run dev

# Production build
docker compose run --rm node npm run build
```

### Artisan Commands

```bash
# Install package
docker compose run --rm cli php artisan install:pjstarter

# Sync permissions
docker compose run --rm cli php artisan pjstarter:permissions:sync
```

## Code Style Requirements

### PHP Conventions (Slevomat Coding Standard)

1. **Strict Types Required:**
   ```php
   <?php

   declare(strict_types = 1);
   ```

2. **Type Declarations:** Required on all properties, parameters, and return types

3. **Constructor Property Promotion:** Required
   ```php
   public function __construct(
       private readonly ArticleRepository $repository,
   ) {}
   ```

4. **Class Length:** Maximum 300 lines

5. **Strict Equality:** Always use `===` and `!==`

6. **Trailing Commas:** Required in arrays and multi-line parameters

7. **Static Closures:** Use when `$this` is not needed

8. **No Commented Code:** Remove unused code instead of commenting

9. **Visibility:** All properties and constants must have explicit visibility

10. **Use Statements:** Alphabetically sorted

### Namespace Structure

```php
namespace Patrikjak\Starter\{Feature}\{Type};
// Example: Patrikjak\Starter\Http\Controllers\Articles\ArticlesController
```

## Architecture Patterns

### Repository Pattern
- Interfaces in `Repositories/Contracts/{Feature}/`
- Implementations in `Repositories/Eloquent/{Feature}/` with `Eloquent` prefix
- Example: `EloquentArticleRepository` implements `ArticleRepository` contract
- Always inject contracts, not implementations
- Bindings configured in `StarterServiceProvider`

### Service Layer
- Business logic in `Services/` classes
- Use `readonly` keyword for immutable services
- Inject repositories, return DTOs or void

### DTOs (Data Transfer Objects)
- Located in `Dto/` organized by feature
- Used for validated input and data transformation

### Policies
- Extend `BasePolicy` for common actions
- Define `FEATURE_NAME` constant in each policy
- `BasePolicy` constants: `VIEW_ANY`, `VIEW`, `CREATE`, `EDIT`, `DELETE`
- Some policies define additional constants (e.g., `RolePolicy::MANAGE`, `RolePolicy::MANAGE_PROTECTED`)
- Permission format: `{action}-{feature}` (e.g., `viewAny-article`, `edit-static_page`)
- When `auth` feature is disabled, all policies are bypassed via `Gate::before()`

### Models
- Use `HasUuids` trait for UUID primary keys
- Implement interfaces: `Metadatable`, `Sluggable`, `Visitable`
- Use custom casts for complex types

## Testing Guidelines

### Test Structure
- Extend `Patrikjak\Starter\Tests\TestCase`
- Feature tests in `tests/Feature/`, unit tests in `tests/Unit/`
- Test methods prefixed with `test` (e.g., `testPageCanBeRendered`)
- **Tests are organized by controller and action**: `tests/Feature/Http/Controllers/{ControllerName}/{ActionTest}.php`
  - e.g., `StaticPagesController/IndexTest.php`, `ArticlesController/CreateTest.php`
- API tests follow the same pattern under `Api/` subdirectory

### Test Utilities
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

### ConfigSetter Trait Methods
Available methods for `#[DefineEnvironment()]`:
- `enableAuth` / `disableAuth`
- `enableDashboard` / `disableDashboard`
- `enableProfile` / `disableProfile`
- `enableStaticPages` / `disableStaticPages`
- `enableArticles` / `disableArticles`
- `enableUsers` / `disableUsers`
- `enableAllFeatures` / `disableAllFeatures`

### Snapshot Testing
- Use `assertMatchesHtmlSnapshot()` for HTML responses
- Use `assertMatchesJsonSnapshot()` for JSON responses
- Update snapshots with `--update-snapshots` flag

### Test Example
```php
<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\StaticPagesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
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

## Key Configuration

### Feature Toggles (`config/pjstarter.php`)
```php
'features' => [
    'auth' => true,          // Toggle authentication (false = open access, auth routes return 404)
    'dashboard' => true,
    'profile' => true,
    'static_pages' => false,  // Disabled by default
    'articles' => false,      // Disabled by default
    'users' => false,         // Disabled by default
],
```

When `auth` is `false`:
- Auth routes (`/login`, `/register`, `/password/*`, `/api/logout`) return 404
- Admin routes are accessible without authentication
- All policy checks are bypassed (`Gate::before()` returns `true`)
- Profile and change-password routes are disabled
- Navigation hides user section and logout button

### Permissions System
- Defined in `PermissionsDefinition` trait (`src/Models/Users/PermissionsDefinition.php`)
- Format: `{action}-{feature}`
- Actions from `BasePolicy`: `viewAny`, `view`, `create`, `edit`, `delete`
- Additional actions per policy (e.g., `manage`, `manageProtected`, `viewSuperadmin`)
- Features defined per policy class via `FEATURE_NAME` constant

## Docker Environment

This project uses Docker for all development tasks. **All commands must be executed via Docker Compose.**

Available Docker services:
- `cli` - PHP CLI environment for running tests, code style checks, and artisan commands
- `node` - Node.js environment for frontend builds

**IMPORTANT:** Never run commands directly on the host machine (e.g., `php`, `composer`, `npm`, `artisan`). Always use the Docker Compose wrapper.

## CI/CD Pipeline

GitHub Actions runs on every push:
1. Install Composer dependencies
2. Build frontend assets (`npm install && npm run build`)
3. Run PHPCS (code style)
4. Run PHPStan (static analysis - Level 6)
5. Run PHPUnit tests
6. Upload coverage to Codecov

## Important Files

| File | Purpose |
|------|---------|
| `src/StarterServiceProvider.php` | Registers services, routes, policies, auth toggling |
| `config/pjstarter.php` | Package configuration with feature toggles |
| `tests/TestCase.php` | Base test class with utilities |
| `tests/Traits/ConfigSetter.php` | Feature toggle helpers for tests |
| `phpunit.xml` | Test configuration |
| `phpstan.neon` | Static analysis config (Level 6) |
| `ruleset.xml` | PHP code style rules |
| `vite.config.js` | Frontend build configuration |

## Development Workflow

1. Write tests for new functionality first
2. Implement the feature
3. Run tests: `docker compose run --rm cli vendor/bin/phpunit`
4. Check code style: `docker compose run --rm cli vendor/bin/phpcs --standard=ruleset.xml`
5. Fix style issues: `docker compose run --rm cli vendor/bin/phpcbf --standard=ruleset.xml`
6. Run static analysis: `docker compose run --rm cli php -d memory_limit=2G vendor/bin/phpstan analyse`
7. Commit and push changes

## Common Patterns

### Controller Action
```php
public function edit(Article $article): View
{
    return view('pjstarter::pages.articles.edit', [
        'article' => $article,
    ]);
}
```

### Service Method
```php
public function create(ArticleData $data): Article
{
    return $this->repository->create($data);
}
```

### Form Request
```php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:255'],
        'status' => ['required', Rule::enum(ArticleStatus::class)],
    ];
}
```

### Policy Method
```php
// BasePolicy handles common actions via hasPermission()
// Each policy defines FEATURE_NAME, BasePolicy delegates automatically:
public function viewAny(User $user, ?Model $model = null): bool
{
    return $this->hasPermission($user, self::VIEW_ANY);
}

public function hasPermission(User $user, string $action): bool
{
    return $user->hasPermission(static::FEATURE_NAME, $action);
}
```

### Repository Implementation
```php
// Contract (Repositories/Contracts/Articles/ArticleRepository.php)
interface ArticleRepository extends SupportsPagination
{
    public function getAllContents(): Collection;
    public function create(ArticleInputData $data, ArticleProcessedData $processed): void;
}

// Implementation (Repositories/Eloquent/Articles/EloquentArticleRepository.php)
class EloquentArticleRepository implements ArticleRepository
{
    public function getAllContents(): Collection
    {
        return Article::get(['content']);
    }
}
```
