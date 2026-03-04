# Configuration

## Feature Toggles (`config/pjstarter.php`)

```php
'features' => [
    'auth' => true,           // Toggle authentication (false = open access, auth routes return 404)
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

## Permissions System

Defined in `PermissionsDefinition` trait (`src/Models/Users/PermissionsDefinition.php`).

- Format: `{action}-{feature}` (e.g., `viewAny-article`, `edit-static_page`)
- Actions from `BasePolicy`: `viewAny`, `view`, `create`, `edit`, `delete`
- Additional actions per policy (e.g., `manage`, `manageProtected`, `viewSuperadmin`)
- Features defined per policy class via `FEATURE_NAME` constant

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

## CI/CD Pipeline

GitHub Actions runs on every push:
1. Install Composer dependencies
2. Build frontend assets (`npm install && npm run build`)
3. Run PHPCS (code style)
4. Run PHPStan (static analysis - Level 6)
5. Run PHPUnit tests
6. Upload coverage to Codecov
