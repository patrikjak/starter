<?php

return [

    /**
     * Name of the application - used in the title
     */
    'app_name' => env('APP_NAME', 'App'),

    /**
     * Path to the icon - relative to the public directory
     * Set to null to disable the icon
     */
    'icon' => [
        'path' => 'images/logo/favicon.svg',
        'type' => 'image/svg+xml',
    ],

    'features' => [
        'auth' => true,
        'dashboard' => true,
        'profile' => true,
        'static_pages' => false,
        'articles' => false,
        'users' => false,
        'content_images' => false,
    ],

    /*
     * Additional content contexts for the content image upload/fetch endpoints.
     * The built-in 'articles' context is registered automatically when articles are enabled.
     * Add custom contexts here to extend the registry with your own model types.
     *
     * 'disk' is optional — omit it to use the application's default filesystem disk.
     *
     * Example:
     * 'content_contexts' => [
     *     'products' => [
     *         'directory' => 'products/images',
     *         'model'     => \App\Models\Product::class,
     *         'disk'      => 's3',
     *     ],
     * ],
     */
    'content_contexts' => [],

    /*
     * Navigation settings, items are displayed in the sidebar
     */
    'navigation' => [
        'home' => '/dashboard',
        'items' => [],
        'user_items' => [],
    ],

    /**
     * Title format for the meta title tag
     */
    'meta_title_format' => '{title} | {appName}',

    'models' => [
        'permission' => \Patrikjak\Starter\Models\Users\Permission::class,
    ],

];