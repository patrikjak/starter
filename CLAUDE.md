# CLAUDE.md - AI Assistant Guidelines for Patrikjak Starter

## Project Overview

**Patrikjak Starter** is a Laravel package (v0.3.0) that provides a reusable admin panel scaffolding system. It includes built-in support for articles, authors, metadata, static pages, user management, roles, and permissions.

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
├── Feature/Http/Controllers/ # Feature tests for controllers
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
docker compose run --rm cli vendor/bin/phpunit tests/Feature/Http/Controllers/Articles/ArticleControllerTest.php

# Run tests with filter
docker compose run --rm cli vendor/bin/phpunit --filter testCanCreateArticle

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
npm install           # Install dependencies
npm run dev          # Development server with HMR
npm run build        # Production build
```

### Artisan Commands

```bash
php artisan install:pjstarter              # Install package
php artisan pjstarter:permissions:sync     # Sync permissions
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
// Example: Patrikjak\Starter\Http\Controllers\Articles\ArticleController
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
- Permission format: `{action}-{feature}` (e.g., `edit-article`)

### Models
- Use `HasUuids` trait for UUID primary keys
- Implement interfaces: `Metadatable`, `Sluggable`, `Visitable`
- Use custom casts for complex types

## Testing Guidelines

### Test Structure
- Extend `Patrikjak\Starter\Tests\TestCase`
- Feature tests in `tests/Feature/`, unit tests in `tests/Unit/`
- Test methods prefixed with `test` (e.g., `testCanCreateArticle`)

### Test Utilities
```php
// Create authenticated users
$this->createAndActAsSuperAdmin();
$this->createAndActAsAdmin(['edit-article', 'delete-article']);
$this->createAndActAsUser();

// Factory assertions (required for type safety)
$category = ArticleCategory::factory()->create();
assert($category instanceof ArticleCategory);
```

### Snapshot Testing
- Use `assertMatchesHtmlSnapshot()` for HTML responses
- Use `assertMatchesJsonSnapshot()` for JSON responses
- Update snapshots with `--update-snapshots` flag

### Test Example
```php
<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Articles;

use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    public function testCanViewArticleList(): void
    {
        $this->createAndActAsAdmin(['view-article']);

        $response = $this->get(route('admin.articles.index'));

        $response->assertOk();
        $response->assertMatchesHtmlSnapshot();
    }
}
```

## Key Configuration

### Feature Toggles (`config/pjstarter.php`)
```php
'features' => [
    'dashboard' => true,
    'profile' => true,
    'static_pages' => true,
    'articles' => true,
    'users' => true,
],
```

### Permissions System
- Defined in `PermissionsDefinition.php`
- Format: `{action}-{feature}`
- Actions from `BasePolicy`: `view`, `create`, `edit`, `delete`, `manage`
- Features defined per policy class

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
| `src/StarterServiceProvider.php` | Registers services, routes, policies |
| `config/pjstarter.php` | Package configuration |
| `tests/TestCase.php` | Base test class with utilities |
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
public function edit(User $user, Article $article): bool
{
    return $user->hasPermission(self::EDIT . '-' . self::FEATURE_NAME);
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
