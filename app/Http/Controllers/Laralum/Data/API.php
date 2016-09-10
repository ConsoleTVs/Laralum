<?php

$api = [
    'users' => [
        'show' => [
            'id', 'name', 'email', 'active', 'banned', 'country_code', 'created_at', 'updated_at'
        ],
        'enabled' => true,
    ],
    'users_settings' => [
        'show' => [
            'default_role', 'location', 'register_enabled', 'default_active', 'welcome_email'
        ],
        'enabled' => true,
    ],
    'roles' => [
        'show' => [
            'id', 'name', 'color', 'assignable', 'allow_editing', 'created_at', 'updated_at'
        ],
        'enabled' => true,
    ],
    'permissions' => [
        'show' => [
            'id', 'slug', 'color', 'assignable', 'created_at', 'updated_at'
        ],
        'enabled' => true,
    ],
    'blogs' => [
        'show' => [
            'id', 'name', 'views_location', 'user_id', 'created_at', 'updated_at'
        ],
        'enabled' => true,
    ],
    'posts' => [
        'show' => [
            'id', 'image', 'title', 'description', 'body', 'user_id', 'blog_id', 'edited_by', 'created_at', 'updated_at'
        ],
        'enabled' => true,
    ],
    'post_comments' => [
        'show' => [
            'id', 'user_id', 'post_id', 'name', 'email', 'content', 'created_at', 'updated_at'
        ],
        'enabled' => true,
    ],
    'documents' => [
        'show' => [
            'id', 'user_id', 'name', 'slug', 'disabled', 'download_timer', 'downloads', 'created_at', 'updated_at'
        ],
        'enabled' => true,
    ],
    'settings' => [
        'show' => [
            'laralum_version', 'website_title', 'logo'
        ],
        'enabled' => true,
    ],
];
