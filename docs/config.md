# Configuration

The PJStarter package provides a comprehensive configuration file that allows you to customize various aspects of your application. This document outlines all available configuration options.

## Basic Configuration

### Application Name
```php
'app_name' => env('APP_NAME', 'App'),
```
The name of your application, used in titles and navigation. Defaults to 'App' if not set in environment variables.

### Icon Configuration
```php
'icon' => [
    'path' => 'images/logo/favicon.svg',
    'type' => 'image/svg+xml',
],
```
Configure your application's favicon:
- `path`: Relative path to the icon file in the public directory
- `type`: MIME type of the icon
- Set `path` to `null` to disable the icon

## Features

The `features` array allows you to enable or disable various package features:

```php
'features' => [
    'dashboard' => true,    // Enable/disable dashboard feature
    'profile' => true,      // Enable/disable user profile feature
    'static_pages' => false, // Enable/disable static pages feature
],
```

### Available Features

1. **Dashboard** (`dashboard`)
   - Enables the main dashboard page
   - Provides a central hub for your application
   - Accessible via `/dashboard` route

2. **Profile** (`profile`)
   - Enables user profile management
   - Includes profile viewing and password change functionality
   - Accessible via `/profile` and `/change-password` routes

3. **Static Pages** (`static_pages`)
   - Enables static page management system
   - Includes metadata and slug management
   - Provides CRUD operations for static pages
   - Accessible via `/static-pages` routes
   - When enabled, also enables:
     - Metadata management
     - Slug management
     - Related migrations

## Navigation Configuration

```php
'navigation' => [
    'home' => '/dashboard',  // Default home route
    'items' => [],          // Custom navigation items
    'user_items' => [],     // Custom user menu items
],
```

### Navigation Options

1. **Home Route** (`home`)
   - Defines the default landing page
   - Can be a string URL or a closure returning a URL
   - Defaults to '/dashboard'

2. **Custom Navigation Items** (`items`)
   - Array of custom navigation items
   - Can include static items or closures
   - The authenticated User is available in closure callbacks
   - Example:
   ```php
   'items' => [
       'feature' => new NavigationItem('Feature', 'feature-url'),
       'dynamic' => static function () {
           return new NavigationItem('Dynamic', 'dynamic-url');
       },
       'user_specific' => static function (User $user) {
           if ($user->hasRole(RoleType::ADMIN)) {
               return new NavigationItem('Admin Only', 'admin-url');
           }
           return null;
       }
   ],
   ```

3. **User Menu Items** (`user_items`)
   - Array of items in the user dropdown menu
   - Similar structure to main navigation items
   - The authenticated User is available in closure callbacks
   - Example:
   ```php
   'user_items' => [
       'settings' => new NavigationItem('Settings', 'settings-url'),
       'preferences' => function() {
           return new NavigationItem('Preferences', 'preferences-url');
       },
       'admin_panel' => function(User $user) {
           if ($user->isAdmin()) {
               return new NavigationItem('Admin Panel', 'admin-panel-url');
           }
           return null;
       }
   ],
   ```

## Metadata Configuration

```php
'meta_title_format' => '{title} | {appName}',
```
Configures the format for page titles:
- `{title}`: The page-specific title
- `{appName}`: Your application name
- Example: "Dashboard | My App"

## Publishing Configuration

To use the configuration, you need to publish the config file:

```bash
php artisan vendor:publish --tag=pjstarter-config
```

### Recommended Composer Script

Add this to your `composer.json` to automatically publish config on updates:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=pjstarter-config --ansi --force"
    ]
}
```

## Feature Dependencies

Some features have dependencies that are automatically handled:

1. **Static Pages Feature**
   - When enabled, automatically enables:
     - Metadata management
     - Slug management
   - Publishes required migrations
   - Sets up necessary routes

2. **Profile Feature**
   - Requires authentication
   - Integrates with user management
   - Provides password change functionality

## Best Practices

1. **Feature Management**
   - Enable only the features you need
   - Disable unused features to reduce overhead
   - Consider dependencies when enabling features

2. **Navigation**
   - Keep navigation items organized and logical
   - Use closures for dynamic items when needed
   - Consider user roles and permissions
   - Leverage User availability in callbacks for role-based navigation

3. **Metadata**
   - Use consistent title formats
   - Include application name in titles
   - Consider SEO implications

4. **Configuration Updates**
   - Always backup your config file before updates
   - Test changes in a development environment
   - Document custom configurations 