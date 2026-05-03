# Consuming Applications

## Required Configuration

```php
// config/auth.php
'providers' => [
    'users' => [
        'model' => Patrikjak\Starter\Models\Users\User::class,
    ],
],
```

## Database Setup Order

Permissions must exist before roles can be assigned.

```bash
# 1. Run migrations
php artisan migrate

# 2. Seed user roles (SUPERADMIN, ADMIN, USER)
php artisan pjauth:sync-roles

# 3. Sync permissions from PermissionsDefinition
php artisan pjstarter:permissions:sync

# 4. Seed application data
php artisan db:seed
```

### Seeder Order

1. **RoleSeeder** — run `php artisan pjauth:sync-roles` to create the default roles defined in `config/pjauth.php` under `default_roles`, then `php artisan pjstarter:permissions:sync` to attach permissions
2. **UserSeeder** — create users with role assignments
3. **Content seeders** — authors, article categories, articles, static pages, etc.

## Upgrading from Integer Roles to UUIDs

If your app was created before roles used UUIDs (i.e. `roles.id` is an integer), run `php artisan migrate` to apply the upgrade migrations automatically.

**What the upgrade does:**

- Converts `roles.id` from integer to UUID
- Assigns `slug` and `is_superadmin` to each role based on `config('pjauth.default_roles')`
- **Deletes roles whose slug is not defined in config** — only roles listed in `default_roles` are kept
- Remaps `permission_role.role_id` to the new UUIDs
- Re-adds the foreign key constraint

**Important:** Roles created by the old `seed:user-roles` command used uppercase names (`SUPERADMIN`, `ADMIN`, `USER`). The migration derives slugs from these names (`superadmin`, `admin`, `user`) and matches them against your config. Any role whose derived slug is not in `default_roles` will be **deleted along with its permission assignments**.

Make sure your `config/pjauth.php` `default_roles` contains all roles you want to keep before running the migration.

**Users assigned to deleted roles** are automatically reassigned to the role defined in `config('pjauth.default_role_slug')` (defaults to `admin`).

### Full Reset (demo app)

```bash
docker compose exec web bash -c "php artisan migrate:fresh --force && php artisan pjauth:sync-roles && php artisan pjstarter:permissions:sync && php artisan db:seed"
```

## Extending Package Models

When a consuming app extends a package model, Laravel's `getMorphClass()` returns the child class name by default. This breaks morph relationships (slugs, metadata) because the package repositories query by the base model class name.

**Required fix in every extending model:**

```php
class ArticleCategory extends BaseArticleCategory
{
    public function getMorphClass(): string
    {
        return BaseArticleCategory::class;
    }
}
```

This applies to all models that participate in morph relationships (`Sluggable`, `Metadatable`).

### Data Migration

If records were created before this fix, update existing morph records:

```sql
UPDATE slugs
SET sluggable_type = 'Patrikjak\Starter\Models\Articles\ArticleCategory'
WHERE sluggable_type = 'App\Models\ArticleCategory';

UPDATE metadata
SET metadatable_type = 'Patrikjak\Starter\Models\Articles\ArticleCategory'
WHERE metadatable_type = 'App\Models\ArticleCategory';
```
