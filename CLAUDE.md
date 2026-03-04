# CLAUDE.md - Patrikjak Starter

Laravel package providing a reusable admin panel scaffolding system with articles, authors, metadata, static pages, user management, roles, and permissions. Authentication can be fully disabled via config.

- **PHP:** 8.4 | **Laravel:** 12.x
- **Dependencies:** `patrikjak/utils`, `patrikjak/auth`

## Docs

- [`docs/architecture.md`](docs/architecture.md) — codebase structure, patterns (repository, service, policy, controller)
- [`docs/testing.md`](docs/testing.md) — test structure, utilities, snapshot testing, ConfigSetter methods
- [`docs/configuration.md`](docs/configuration.md) — feature toggles, permissions system, important files, CI/CD
- [`docs/consuming-apps.md`](docs/consuming-apps.md) — database setup order, extending models, morph class fix

## Docker

**All commands run via Docker.** Never run `php`, `composer`, `npm`, or `artisan` directly on the host.

- `cli` — PHP CLI (tests, linting, artisan)
- `node` — Node.js (frontend builds)

## Quick Commands

```bash
# Tests
docker compose run --rm cli vendor/bin/phpunit
docker compose run --rm cli vendor/bin/phpunit --filter testMethodName

# Code style
docker compose run --rm cli vendor/bin/phpcs --standard=ruleset.xml
docker compose run --rm cli vendor/bin/phpcbf --standard=ruleset.xml

# Static analysis (PHPStan level 6)
docker compose run --rm cli php -d memory_limit=2G vendor/bin/phpstan analyse

# Frontend
docker compose run --rm node npm run build
docker compose run --rm node npm run dev

# Artisan
docker compose run --rm cli php artisan install:pjstarter
docker compose run --rm cli php artisan seed:user-roles
docker compose run --rm cli php artisan pjstarter:permissions:sync
```

## Architecture

Repository pattern (`Contracts/` → `Eloquent/`), thin controllers, service layer for business logic, `final readonly` DTOs. See [`docs/architecture.md`](docs/architecture.md).

## Testing

PHPUnit with Orchestra Testbench. Tests organized by controller action (`tests/Feature/Http/Controllers/{Controller}/{Action}Test.php`). Snapshot testing via `spatie/phpunit-snapshot-assertions`. See [`docs/testing.md`](docs/testing.md).

## Development Workflow

1. Implement the feature
2. Write tests
3. Run tests, PHPCS, PHPStan — all must pass
4. Update `CHANGELOG.md` under `[Unreleased]`
5. Commit

## Key Gotcha: Extending Models

When a consuming app extends a package model, override `getMorphClass()` to return the base class — otherwise morph relationships (slugs, metadata) break. See [`docs/consuming-apps.md`](docs/consuming-apps.md).
