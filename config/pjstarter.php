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
        'dashboard' => true,
        'profile' => true,
        'static_pages' => false,
        'articles' => false,
        'users' => false,
    ],

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

];