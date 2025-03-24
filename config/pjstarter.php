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
        'static_pages' => true,
    ],

    /*
     * Navigation settings, items are displayed in the sidebar
     */
    'navigation' => [
        'home' => '/dashboard',
        'items' => [],
        'user_items' => [],
    ],

    'meta_title_format' => '{title} | {appName}',

];