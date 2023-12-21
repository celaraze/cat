<?php

return [
    'shield_resource' => [
        'should_register_navigation' => true,
        'slug' => 'roles',
        'navigation_sort' => -1,
        'navigation_badge' => true,
        'navigation_group' => true,
        'is_globally_searchable' => true,
        'show_model_path' => false,
        'is_scoped_to_tenant' => true,
    ],

    'auth_provider_model' => [
        'fqcn' => 'App\\Models\\User',
    ],

    'super_admin' => [
        'enabled' => true,
        'name' => '超级管理员',
        'define_via_gate' => false,
        'intercept_gate' => 'before', // after
    ],

    'panel_user' => [
        'enabled' => true,
        'name' => 'panel_user',
    ],

    'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ],

        'page' => 'page',
        'widget' => 'widget',
    ],

    'entities' => [
        'pages' => false,
        'widgets' => false,
        'resources' => true,
        'custom_permissions' => false,
    ],

    'generator' => [
        'option' => 'policies_and_permissions',
    ],

    'exclude' => [
        'enabled' => true,

        'pages' => [

        ],

        'widgets' => [

        ],

        'resources' => [],
    ],

    'discovery' => [
        'discover_all_resources' => false,
        'discover_all_widgets' => false,
        'discover_all_pages' => false,
    ],

    'register_role_policy' => [
        'enabled' => true,
    ],

];
