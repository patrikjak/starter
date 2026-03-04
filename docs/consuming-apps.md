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
php artisan seed:user-roles

# 3. Sync permissions from PermissionsDefinition
php artisan pjstarter:permissions:sync

# 4. Seed application data
php artisan db:seed
```

### Seeder Order

1. **RoleSeeder** — create roles (IDs must match `RoleType` enum: 1=SUPERADMIN, 2=ADMIN, 3=USER) and attach permissions
2. **UserSeeder** — create users with role assignments
3. **Content seeders** — authors, article categories, articles, static pages, etc.

### Full Reset (demo app)

```bash
docker compose exec web bash -c "php artisan migrate:fresh --force && php artisan seed:user-roles && php artisan pjstarter:permissions:sync && php artisan db:seed"
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
