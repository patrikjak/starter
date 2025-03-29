# Web Header Component

The metadata header component (`x-pjstarter::layout.header`) is a specialized component that handles SEO-related metadata in your application. It automatically manages various metadata elements and integrates with your `Metadatable` models to provide dynamic metadata based on the current URL.

## Usage

```blade
<x-pjstarter::layout.header />
```

## Features

### Automatic Metadata Management
The component automatically handles:
- Page title
- Meta description
- Meta keywords
- Canonical URL
- Structured data
- Additional meta tags (via `@stack('meta')`)
- Font loading (via `@stack('fonts')`)
- Favicon (via `@stack('favicons')`)
- Styles (via `@stack('styles')`)
- Scripts (via `@stack('scripts')`)

### Dynamic Metadata
The component automatically pulls metadata from your `Metadatable` models if they are available for the current URL. It uses the `SlugService` to determine the appropriate metadata based on the current URL.

## Integration with Metadatable Models

To use the metadata header component effectively, your models should implement the `Metadatable` interface:

```php
interface Metadatable
{
    public function getMetadata(): Metadata;
}
```

The `getMetadata()` method should return a `Metadata` object containing:
- title
- description
- keywords
- canonical_url
- structured_data

## Stack Usage

The component provides several stack points for additional customization:

1. `@stack('meta')` - For additional meta tags
2. `@stack('fonts')` - For custom font loading
3. `@stack('favicons')` - For favicon customization
4. `@stack('styles')` - For additional stylesheets
5. `@stack('scripts')` - For additional scripts

## Example Usage

### Basic Implementation
```blade
<x-pjstarter::layout.header />
```

### With Custom Meta Tags
```blade
@push('meta')
    <meta name="author" content="Your Name">
    <meta name="robots" content="index, follow">
@endpush

<x-pjstarter::layout.header />
```

### With Custom Styles
```blade
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

<x-pjstarter::layout.header />
```