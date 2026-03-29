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

## Custom Navigation Items & Groups

Custom navigation entries are configured via `config/pjstarter.php` under `navigation.items`.

### Plain items

Plain `NavigationItem` instances are appended to the built-in **Management** group:

```php
use Patrikjak\Starter\Dto\Common\NavigationItem;

'navigation' => [
    'items' => [
        new NavigationItem('My Page', '/admin/my-page', icon: '<svg>...</svg>'),
    ],
],
```

### Custom groups

Wrap items in a `NavigationGroup` to render them as their own labelled sidebar section, placed after the built-in groups:

```php
use Patrikjak\Starter\Dto\Common\NavigationGroup;
use Patrikjak\Starter\Dto\Common\NavigationItem;

'navigation' => [
    'items' => [
        new NavigationGroup('My Section', [
            new NavigationItem('Page A', '/admin/page-a', icon: '<svg>...</svg>'),
            new NavigationItem('Page B', '/admin/page-b'),
        ]),
        new NavigationGroup('Another Section', [
            new NavigationItem('Page C', '/admin/page-c'),
        ]),
    ],
],
```

Both plain items and groups can be mixed freely in the same array. Closures that receive the authenticated `User` and return a `NavigationItem|null` are also supported (for conditional visibility):

```php
'items' => [
    static fn (User $user): ?NavigationItem =>
        $user->isAdmin() ? new NavigationItem('Admin Only', '/admin/secret') : null,
],
```

### User dropdown items

`navigation.user_items` accepts the same `NavigationItem` / `Closure` values (groups are not applicable here):

```php
'user_items' => [
    new NavigationItem('Settings', '/admin/settings'),
],
```

## CI/CD Pipeline

GitHub Actions runs on every push:
1. Install Composer dependencies
2. Build frontend assets (`npm install && npm run build`)
3. Run PHPCS (code style)
4. Run PHPStan (static analysis - Level 6)
5. Run PHPUnit tests
6. Upload coverage to Codecov
