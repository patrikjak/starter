# Adding New Permissions

This guide explains how to add new permissions to the system. Permissions are defined in the `PermissionsDefinition` trait and are automatically synchronized with the database.

## Basic Structure

Each permission is defined using the `Permission` DTO with the following structure:

```php
new Permission(
    string $action,           // The action name (e.g., 'view', 'create', 'edit', 'delete')
    array $descriptions,      // Translations for the permission description
    bool $protected = false,  // Whether this is a protected permission
    array $defaultRoles = []  // Default roles that should have this permission
)
```

## Step-by-Step Guide

### 1. Define the Permission in Your Policy

First, define the permission constant in your policy class:

```php
class YourFeaturePolicy extends BasePolicy
{
    public const string FEATURE_NAME = 'your-feature';
    
    // Define your custom permission constants
    public const string CUSTOM_ACTION = 'customAction';
}
```

### 2. Add the Permission to PermissionsDefinition

Add your new permission to the `getPermissions()` method in `src/Models/Users/PermissionsDefinition.php`:

```php
public static function getPermissions(): array
{
    return [
        // ... existing permissions ...
        
        new FeaturePermissions(YourFeaturePolicy::FEATURE_NAME, [
            // Basic CRUD permissions
            new Permission(
                BasePolicy::VIEW_ANY,
                [
                    'en' => 'View your features',
                    'sk' => 'Zobraziť vaše funkcie',
                ],
                false,
                [RoleType::SUPERADMIN, RoleType::ADMIN]
            ),
            
            // Custom permission
            new Permission(
                YourFeaturePolicy::CUSTOM_ACTION,
                [
                    'en' => 'Perform custom action',
                    'sk' => 'Vykonať vlastnú akciu',
                ],
                true,  // Protected permission
                [RoleType::SUPERADMIN]  // Only superadmin has access by default
            ),
        ]),
    ];
}
```

### 3. Use the Permission in Your Policy

Implement the permission check in your policy class:

```php
class YourFeaturePolicy extends BasePolicy
{
    public function customAction(User $user, YourModel $model): bool
    {
        return $user->hasPermission(self::FEATURE_NAME, self::CUSTOM_ACTION);
    }
}
```

### 4. Synchronize Permissions

After adding new permissions, you need to synchronize them with the database. You can do this in two ways:

#### Using Artisan Command
```bash
php artisan pjstarter:sync-permissions
```

#### Using the Service
```php
use Patrikjak\Starter\Services\Users\PermissionSynchronizer;

class YourService
{
    public function __construct(
        private readonly PermissionSynchronizer $permissionSynchronizer
    ) {}

    public function sync(): void
    {
        $this->permissionSynchronizer->synchronize();
    }
}
```

## Best Practices

1. **Naming Conventions**
   - Use clear, descriptive names for permissions
   - Follow the pattern: `action-feature` (e.g., 'view-articles', 'edit-users')
   - Use constants for permission names to avoid typos

2. **Protected Permissions**
   - Set `protected = true` for sensitive permissions
   - Protected permissions can only be managed by superadmins
   - Use sparingly and document why a permission is protected

3. **Default Roles**
   - Assign permissions to appropriate default roles
   - Consider the principle of least privilege
   - SUPERADMIN should have access to all permissions
   - ADMIN should have access to most non-protected permissions
   - USER should have minimal permissions

4. **Translations**
   - Always provide translations for all supported languages
   - Keep descriptions clear and concise
   - Use consistent terminology across permissions

## Example: Adding a New Feature with Permissions

Here's a complete example of adding permissions for a new "Reports" feature:

```php
// 1. Create the Policy
class ReportPolicy extends BasePolicy
{
    public const string FEATURE_NAME = 'report';
    public const string EXPORT = 'export';
    public const string SCHEDULE = 'schedule';
}

// 2. Add to PermissionsDefinition
new FeaturePermissions(ReportPolicy::FEATURE_NAME, [
    new Permission(
        BasePolicy::VIEW_ANY,
        [
            'en' => 'View reports',
            'sk' => 'Zobraziť reporty',
        ],
        false,
        [RoleType::SUPERADMIN, RoleType::ADMIN]
    ),
    new Permission(
        ReportPolicy::EXPORT,
        [
            'en' => 'Export reports',
            'sk' => 'Exportovať reporty',
        ],
        false,
        [RoleType::SUPERADMIN, RoleType::ADMIN]
    ),
    new Permission(
        ReportPolicy::SCHEDULE,
        [
            'en' => 'Schedule reports',
            'sk' => 'Naplánovať reporty',
        ],
        true,  // Protected - only superadmin can schedule reports
        [RoleType::SUPERADMIN]
    ),
])

// 3. Implement in Policy
class ReportPolicy extends BasePolicy
{
    public function export(User $user, Report $report): bool
    {
        return $user->hasPermission(self::FEATURE_NAME, self::EXPORT);
    }

    public function schedule(User $user, Report $report): bool
    {
        return $user->hasPermission(self::FEATURE_NAME, self::SCHEDULE);
    }
}
```

## Troubleshooting

1. **Permission Not Working**
   - Check if the permission is properly synchronized
   - Verify the permission name matches exactly
   - Ensure the user has the correct role
   - Check if the permission is protected

2. **Synchronization Issues**
   - Clear the cache: `php artisan cache:clear`
   - Check the database for existing permissions
   - Verify the permission definitions are correct
   - Check for any migration issues

3. **Common Mistakes**
   - Incorrect permission name format
   - Missing translations
   - Incorrect role assignments
   - Not running synchronization after adding permissions 