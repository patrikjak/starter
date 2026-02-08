# Extensibility

The PJStarter package is designed to be extendable. This guide explains how to override default behavior when your project needs additional data or custom logic beyond what the package provides.

## Service Provider Order

The package registers default bindings for repositories and other services. To override them, your application's service provider **must be registered after** `StarterServiceProvider` in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    Patrikjak\Utils\UtilsServiceProvider::class,
    Patrikjak\Auth\AuthServiceProvider::class,
    Patrikjak\Starter\StarterServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class, // after StarterServiceProvider
];
```

Bindings registered later overwrite earlier ones. If your provider loads before `StarterServiceProvider`, the package will overwrite your custom bindings.

## Extending Models

When you need additional columns or custom properties on a package model, create a model in your application that extends the vendor model.

### Example: Adding `text_color` to ArticleCategory

```php
namespace App\Models;

use Patrikjak\Starter\Models\Articles\ArticleCategory as BaseArticleCategory;

/**
 * @property string $text_color
 */
class ArticleCategory extends BaseArticleCategory
{
    protected $table = 'article_categories';
}
```

Key points:
- Set `$table` explicitly — Laravel derives table name from the class, so without it the extended model would look for an `article_categories` table under a different convention
- Add `@property` PHPDoc for IDE autocompletion and static analysis
- The extended model inherits all relationships, traits, and observers from the base model
- Use your app model in repositories and views instead of the vendor model

## Extending DTOs

DTOs (Data Transfer Objects) are `readonly` classes that can be extended to carry additional data.

### Example: Adding `text_color` to ArticleCategory

**1. Create an extended DTO:**

```php
namespace App\Dto;

use Patrikjak\Starter\Dto\Articles\ArticleCategoryData as BaseArticleCategoryData;

readonly class ArticleCategoryData extends BaseArticleCategoryData
{
    public function __construct(
        string $name,
        ?string $description = null,
        public string $textColor = '#15791e',
    ) {
        parent::__construct($name, $description);
    }
}
```

## Extending Form Requests

To validate and capture additional fields, extend the package's FormRequest and override `getArticleCategoryData()` to return your extended DTO.

**1. Create an extended request:**

```php
namespace App\Http\Requests;

use App\Dto\ArticleCategoryData;
use Patrikjak\Starter\Http\Requests\Articles\StoreArticleCategoryRequest as BaseRequest;

class StoreArticleCategoryRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'text_color' => ['nullable', 'max:7'],
        ]);
    }

    public function getArticleCategoryData(): ArticleCategoryData
    {
        return new ArticleCategoryData(
            $this->input('name'),
            $this->input('description'),
            $this->input('text_color') ?: '#15791e',
        );
    }
}
```

**2. Bind the extended request in `AppServiceProvider`:**

```php
use App\Http\Requests\StoreArticleCategoryRequest;
use Patrikjak\Starter\Http\Requests\Articles\StoreArticleCategoryRequest as VendorRequest;

public function register(): void
{
    $this->app->bind(VendorRequest::class, StoreArticleCategoryRequest::class);
}
```

The package controllers type-hint the base request class. Laravel resolves type-hinted FormRequests through the container, so your binding ensures the extended request is used — including its validation rules and DTO factory method.

## Extending Repositories

Repositories handle data persistence. To add custom fields, extend the base repository and override `create()` / `update()`.

**1. Create an extended repository:**

```php
namespace App\Repositories\Eloquent;

use App\Dto\ArticleCategoryData;
use App\Models\ArticleCategory;
use Patrikjak\Starter\Dto\Articles\ArticleCategoryData as BaseArticleCategoryData;
use Patrikjak\Starter\Repositories\Articles\ArticleCategoryRepository as BaseRepository;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository as RepositoryContract;

final class EloquentArticleCategoryRepository extends BaseRepository implements RepositoryContract
{
    public function create(BaseArticleCategoryData $articleCategory): void
    {
        $model = new ArticleCategory();
        $model->name = $articleCategory->name;
        $model->description = $articleCategory->description;

        if ($articleCategory instanceof ArticleCategoryData) {
            $model->text_color = $articleCategory->textColor;
        }

        $model->save();
    }

    public function update(string $id, BaseArticleCategoryData $articleCategoryData): void
    {
        $model = ArticleCategory::findOrFail($id);

        $model->name = $articleCategoryData->name;
        $model->description = $articleCategoryData->description;

        if ($articleCategoryData instanceof ArticleCategoryData) {
            $model->text_color = $articleCategoryData->textColor;
        }

        $model->save();
    }
}
```

Note: Use your app's `App\Models\ArticleCategory` model instead of the vendor model. This ensures the `text_color` property is properly recognized.

**2. Bind in a service provider that loads after `StarterServiceProvider`:**

```php
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository as VendorContract;

public array $bindings = [
    VendorContract::class => \App\Repositories\Eloquent\EloquentArticleCategoryRepository::class,
];
```

The `instanceof` check ensures backward compatibility — if the base DTO is passed (e.g., from other parts of the package), the repository still works correctly and only applies custom fields when the extended DTO is provided.

## Publishing Views

To customize admin forms (e.g., adding a `text_color` input), publish only the views you need:

```bash
php artisan vendor:publish --tag=pjstarter-views
```

Then edit only the specific templates in `resources/views/vendor/pjstarter/`. For example, to add a field to the article category form:

```blade
{{-- resources/views/vendor/pjstarter/pages/articles/categories/create.blade.php --}}
<x-pjutils::form method="POST" :action="route('admin.api.articles.categories.store')">
    <x-pjutils::form.input name="name" :label="__('pjstarter::pages.articles.categories.name')" />
    <x-pjutils::form.textarea name="description" :label="__('pjstarter::pages.articles.categories.description')" />
    {{-- Custom field --}}
    <x-pjutils::form.input name="text_color" label="Text color" placeholder="#15791e" />
</x-pjutils::form>
```

## Complete Example

Adding a `text_color` column to article categories end-to-end:

### 1. Migration

```bash
php artisan make:migration add_text_color_to_article_categories_table
```

```php
Schema::table('article_categories', static function (Blueprint $table): void {
    $table->string('text_color')->default('#15791e')->after('description');
});
```

### 2. Extended Model, DTO, FormRequest, Repository

See sections above.

### 3. Service Provider Bindings

`AppServiceProvider` — for request binding:

```php
$this->app->bind(VendorRequest::class, StoreArticleCategoryRequest::class);
```

`RepositoryServiceProvider` (loaded after `StarterServiceProvider`) — for repository binding:

```php
public array $bindings = [
    VendorArticleCategoryRepository::class => EloquentArticleCategoryRepository::class,
];
```

### 4. Published Views

Add the `text_color` input to the published create/edit views.

### 5. Frontend Usage

Access the custom field through the model:

```blade
<span style="color: {{ $article->articleCategory->text_color }}">
    {{ $article->articleCategory->name }}
</span>
```
