# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel package called `patrikjak/starter` - a Laravel admin panel starter that provides common CMS features like articles, static pages, user management, and metadata. It's designed to be installed as a Composer package and integrated into Laravel applications.

The package depends on two other packages:
- `patrikjak/utils` - Utility functions and helpers
- `patrikjak/auth` - Authentication system

## Development Commands

### Testing
```bash
# Run all tests
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit --testsuite Feature
vendor/bin/phpunit --testsuite Unit

# Run a single test file
vendor/bin/phpunit tests/Unit/Services/Articles/ArticleServiceTest.php

# Run with coverage (if xdebug is enabled)
vendor/bin/phpunit --coverage-html coverage
```

### Code Quality
```bash
# Run PHPStan static analysis
vendor/bin/phpstan analyse

# Check coding standards (Slevomat)
vendor/bin/phpcs

# Fix coding standards automatically
vendor/bin/phpcbf
```

### Frontend Development
```bash
# Start Vite dev server
npm run dev

# Build assets for production
npm run build
```

### Package Installation Command
```bash
# Fast setup command for users of this package
php artisan install:pjstarter
```

## Architecture

### Service Provider Pattern
The package uses Laravel's service provider system. `StarterServiceProvider` is the main entry point that:
- Binds repository contracts to implementations in the container
- Publishes assets, views, config, translations, and migrations with specific tags (`pjstarter-*`)
- Loads routes from `routes/web.php` and `routes/api.php`
- Registers Blade components under the `pjstarter` namespace
- Registers policies for authorization
- Defines a custom route binding for `sluggable` parameter that resolves slugs to their respective models

### Layer Architecture
The codebase follows a strict layered architecture:

1. **Controllers** (`src/Http/Controllers/`) - Handle HTTP requests, delegate to services
   - Organized by feature (Articles, Users, StaticPages, etc.)
   - Separate API controllers in `Api/` subdirectories

2. **Services** (`src/Services/`) - Business logic layer
   - Orchestrate operations across repositories
   - Handle file uploads, data transformations, complex business rules
   - Example: `ArticleService` manages article creation/update/deletion and image handling

3. **Repositories** (`src/Repositories/`) - Data access layer
   - All repositories implement contracts from `src/Repositories/Contracts/`
   - Bound to their contracts in `StarterServiceProvider::$bindings`
   - Support pagination via `SupportsPagination` trait
   - Example: `ArticleRepository` handles database operations for articles

4. **Models** (`src/Models/`) - Eloquent models
   - Organized by feature
   - Use custom casts like `EditorjsDataCast` and `TranslatableCast`
   - Observer pattern for metadata and slug management

5. **DTOs** (`src/Dto/`) - Data Transfer Objects for passing data between layers
   - Strongly typed data containers
   - Separate input DTOs (from requests) and processed DTOs (enriched data)

6. **Policies** (`src/Policies/`) - Authorization logic
   - All inherit from `BasePolicy`
   - Registered in `StarterServiceProvider::registerPolicies()`

7. **Form Requests** (`src/Http/Requests/`) - Validation logic

### Feature Toggle System
Features are controlled via `config/pjstarter.php`:
```php
'features' => [
    'dashboard' => true,
    'profile' => true,
    'static_pages' => false,
    'articles' => false,
    'users' => false,
]
```

The service provider conditionally publishes migrations based on enabled features. When adding new features, follow this pattern.

### EditorJS Integration
The package uses EditorJS for rich content editing:
- TypeScript code in `resources/js/articles/`
- Custom cast `EditorjsDataCast` for storing/retrieving editor data
- Factory classes in `src/Factories/Editorjs/` for rendering editor blocks
- DTOs in `src/Dto/Editorjs/` for type-safe block data

### Slug System
The package has a polymorphic slug system:
- `Sluggable` interface for models that support slugs
- `SlugRepository` manages slug lookups
- Custom route binding in service provider resolves slugs to models
- Observers automatically manage slug creation/updates

### Metadata System
Polymorphic metadata support for SEO:
- `Metadata` model with morphTo relationship
- Observers handle automatic metadata management
- View components for rendering meta tags

## Testing

### Test Structure
- Uses Orchestra Testbench for package testing
- Base `TestCase` at `tests/TestCase.php` with common setup
- Tests organized into `Feature/` and `Unit/` directories
- Factory classes in `tests/Factories/` for test data

### Test Environment
- Uses RefreshDatabase trait
- Seeds user roles and permissions in setUp
- Sets Carbon time to 2025-05-18 for consistency
- Loads migrations from all feature directories
- Uses snapshot testing with `spatie/phpunit-snapshot-assertions`

### Test Database
Configured in `phpunit.xml` to use:
- Array cache driver
- Sync queue connection
- Array session/mail drivers
- Testing database

## Code Style & Standards

### PHP Standards
- PHP 8.4+ required
- Strict types declared in all files (`declare(strict_types = 1);`)
- Slevomat Coding Standard enforced via `ruleset.xml`
- PHPStan level 6 with Larastan
- Class length limit: 300 lines
- Constructor property promotion required
- All type hints required (parameters, returns, properties)

### Frontend
- TypeScript for JavaScript files
- SCSS for stylesheets
- Tailwind CSS v4 via Vite
- No HMR during development (Vite refresh disabled)

### Naming Conventions
- Repositories: Interface in `Contracts/`, implementation with same name
- Services: End with `Service` suffix, readonly classes
- DTOs: End with `Data` suffix
- Policies: End with `Policy` suffix
- Controllers: Feature-based organization with optional `Api/` subdirectory

## Important Notes

### Publishing Assets
The package uses tagged publishing. When modifying publishable assets, users need to run:
```bash
php artisan vendor:publish --tag=pjstarter-assets --force
php artisan vendor:publish --tag=pjstarter-config --force
php artisan vendor:publish --tag=pjstarter-views --force
php artisan vendor:publish --tag=pjstarter-migrations --force
```

### Database Migrations
Migrations are organized by feature in `database/migrations/features/`. The service provider only publishes migrations for enabled features. Don't create migrations in the root migrations directory.

### Dependency Injection
All repositories must be injected via their contract interfaces, never concrete implementations. The service provider handles binding contracts to implementations.

### Building Assets
Assets are built to `public/assets/` with specific filename patterns (no hashes). The Vite config disables manifest generation and HMR.