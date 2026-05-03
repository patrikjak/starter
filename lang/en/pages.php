<?php

return [

    'dashboard' => [
        'welcome' => 'Welcome back',
        'welcome_subtitle' => 'Here\'s a quick overview of your content.',
        'welcome_subtitle_empty' => 'Your admin panel is ready.',
        'articles' => 'Articles',
        'categories' => 'Categories',
        'authors' => 'Authors',
        'static_pages' => 'Static pages',
    ],

    'profile' => [
        'title' => 'Profile',
        'change_password' => 'Change password',
        'password_change' => 'Password change',
        'set_new_password' => 'Set new password',
        'member_since' => 'Member since',
        'name' => 'Name',
        'email' => 'Email',
    ],

    'static_pages' => [
        'title' => 'Static pages',
        'name' => 'Page name',
        'prefix' => 'Prefix',
        'url' => 'URL',
        'slug' => 'Slug',
        'new_page' => 'New page',
        'edit_page' => 'Page edit',
        'create_new_page' => 'New page',
        'placeholders' => [
            'prefix' => 'Leave empty for the root page',
        ],
        'metadatable_type' => 'Static page',
        'static_page_deleted' => 'Static page was deleted',
    ],

    'metadata' => [
        'title' => 'SEO settings',
        'meta_title' => 'Meta title',
        'meta_description' => 'Meta description',
        'meta_keywords' => 'Meta keywords',
        'canonical_url' => 'Canonical URL',
        'structured_data' => 'Structured data',
        'page_name' => 'Page name',
        'empty' => 'Empty',
        'edit' => 'Edit SEO settings',
    ],

    'slug' => [
        'prefix' => 'Prefix',
        'url' => 'URL',
        'slug' => 'Slug',
    ],

    'articles' => [
        'title' => 'Articles',
        'create' => 'New article',
        'new' => 'New article',
        'detail' => 'Article detail',
        'content' => 'Content',
        'other_info' => 'Other information',
        'category' => 'Category',
        'author' => 'Author',
        'article_title' => 'Article title',
        'excerpt' => 'Excerpt',
        'featured_image' => 'Featured image',
        'status' => 'Status',
        'visibility' => 'Visibility',
        'read_time' => 'Read time (minutes)',
        'published_at' => 'Published at',
        'private' => 'Private',
        'public' => 'Public',
        'article' => 'Article',
        'statuses' => [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
        ],
        'article_deleted' => 'Article was deleted',
        'edit' => 'Article edit',
        'article_info' => 'Article info',
        'media' => 'Media',
        'publishing' => 'Publishing',

        'accordion' => [
            'basic_info' => 'Basic info',
            'excerpt' => 'Excerpt',
            'media' => 'Media',
        ],

        'index' => [
            'title' => 'All articles',
        ],

        'categories' => [
            'title' => 'Article categories',
            'create_new_category' => 'New category',
            'category' => 'Article category',
            'category_details' => 'Details of the category',
            'name' => 'Name',
            'description' => 'Description',
            'category_deleted' => 'Category was deleted',
            'edit' => 'Category edit',

            'index' => [
                'title' => 'All categories',
            ],
        ],
    ],

    'authors' => [
        'title' => 'Authors',
        'create_new_author' => 'New author',
        'name' => 'Name',
        'profile_picture' => 'Profile picture',
        'author_details' => 'Author details',
        'edit_author' => 'Edit author',
        'author_edit' => 'Author edit',
        'author_deleted' => 'Author was deleted',
    ],

    'users' => [
        'title' => 'Users',
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',

        'invite_user' => 'Invite user',
        'invite_sent' => 'Invite was sent',
        'invite_modal_title' => 'Invite user',

        'change_role' => 'Change role',
        'change_role_modal_title' => 'Change user role',
        'role_updated' => 'User role was updated',

        'index' => [
            'title' => 'All users',
        ],

        'roles' => [
            'title' => 'Roles',
            'name' => 'Name',
            'slug' => 'Slug',
            'manage_permissions' => 'Manage permissions',
            'permissions' => 'Permissions',
            'details' => 'Role details',
            'permissions_synced' => 'Permissions were synced',
            'create' => 'New role',
            'edit' => 'Edit role: :name',
            'role_created' => 'Role was created',
            'role_updated' => 'Role was updated',
            'role_deleted' => 'Role was deleted',
        ],
    ],

];