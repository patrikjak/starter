# Architecture

## Codebase Structure

```
src/
├── Casts/                    # Eloquent attribute casts
├── Console/Commands/         # Artisan commands
├── Dto/                      # DTOs (Articles, Editorjs, Metadata, Slugs, Users)
├── Enums/                    # Backed enums (ArticleStatus, Visibility, BlockType, etc.)
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
│   ├── Http/Controllers/     # Feature tests (by controller, then by action)
│   └── View/                 # View component tests
├── Unit/                     # Unit tests for services/renderers
├── Factories/                # Test-specific factories
├── Traits/                   # Test utilities (WithTestUser, ConfigSetter)
└── TestCase.php

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
```

## Namespace Structure

```php
namespace Patrikjak\Starter\{Feature}\{Type};
// Example: Patrikjak\Starter\Http\Controllers\Articles\ArticlesController
```

## Repository Pattern

Interfaces in `Repositories/Contracts/{Feature}/`, implementations in `Repositories/Eloquent/{Feature}/` with `Eloquent` prefix. Always inject contracts, not implementations. Bindings registered in `StarterServiceProvider`.

```php
// Contract
interface ArticleRepository extends SupportsPagination
{
    public function getAllContents(): Collection;
    public function create(ArticleInputData $data, ArticleProcessedData $processed): void;
}

// Implementation
final class EloquentArticleRepository implements ArticleRepository
{
    public function getAllContents(): Collection
    {
        return Article::get(['content']);
    }
}
```

## Service Layer

Business logic in `Services/`. Use `readonly` for immutable services. Inject repositories, return DTOs or void.

```php
public function create(ArticleData $data): Article
{
    return $this->repository->create($data);
}
```

## DTOs

`final readonly class` in `Dto/` organized by feature. Used for validated input and data transformation.

## Models

- Use `HasUuids` trait for UUID primary keys
- Implement interfaces: `Metadatable`, `Sluggable`, `Visitable`
- Use custom casts for complex types

## Policies

Extend `BasePolicy`. Define `FEATURE_NAME` constant in each policy.

- `BasePolicy` constants: `VIEW_ANY`, `VIEW`, `CREATE`, `EDIT`, `DELETE`
- Some policies add constants (e.g., `RolePolicy::MANAGE`, `RolePolicy::MANAGE_PROTECTED`)
- Permission format: `{action}-{feature}` (e.g., `viewAny-article`, `edit-static_page`)
- When `auth` is disabled, all policies are bypassed via `Gate::before()`

```php
public function viewAny(User $user, ?Model $model = null): bool
{
    return $this->hasPermission($user, self::VIEW_ANY);
}

public function hasPermission(User $user, string $action): bool
{
    return $user->hasPermission(static::FEATURE_NAME, $action);
}
```

## Controller Pattern

Web controllers render views; API controllers handle form submissions and return JSON.

```php
public function edit(Article $article): View
{
    return view('pjstarter::pages.articles.edit', [
        'article' => $article,
    ]);
}
```

## Form Requests

```php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:255'],
        'status' => ['required', Rule::enum(ArticleStatus::class)],
    ];
}
```
