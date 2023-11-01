<?php

use App\Models\User;

return [

    'preload_roles' => true,

    'preload_permissions' => true,

    'navigation_section_group' => '基础数据', // Default uses language constant

    'team_model' => \App\Models\Team::class,

    /*
     * Set to false to remove from navigation
     */
    'should_register_on_navigation' => [
        'permissions' => false,
        'roles' => false,
    ],

    'guard_names' => [
        'web' => 'web',
//        'api' => 'api',
    ],

    'toggleable_guard_names' => [
        'roles' => [
            'isToggledHiddenByDefault' => true,
        ],
        'permissions' => [
            'isToggledHiddenByDefault' => true,
        ],
    ],

    'default_guard_name' => 'web',

    'model_filter_key' => 'return \'%\'.$key;', // Eg: 'return \'%\'.$key.'\%\';'

    'generator' => [

        'guard_names' => [
            'web',
//            'api',
        ],

        'permission_affixes' => [

            /*
             * Permissions Aligned with Policies.
             * DO NOT change the keys unless the genericPolicy.stub is published and altered accordingly
             */
            'viewAnyPermission' => '查看任意',
            'viewPermission' => '查看',
            'createPermission' => '创建',
            'updatePermission' => '更新',
            'deletePermission' => '删除',
            'restorePermission' => '恢复',
            'forceDeletePermission' => '强制删除',

            /*
             * Additional Resource Permissions
             */
//            'replicate' => '复制',
//            'reorder' => '排序',
        ],

        /*
         * returns the "name" for the permission.
         *
         * $permission which is an iteration of [permission_affixes] ,
         * $model The model to which the $permission will be concatenated
         *
         * Eg: 'permission_name' => 'return $permissionAffix . ' ' . Str::kebab($modelName),
         *
         * Note: If you are changing the "permission_name" , It's recommended to run with --clean to avoid duplications
         */
        'permission_name' => 'return $permissionAffix . \' \' . $modelName;',

        /*
         * Permissions will be generated for the models associated with the respective Filament Resources
         */
        'discover_models_through_filament_resources' => false,

        /*
         * Include directories which consists of models.
         */
        'model_directories' => [
            app_path('Models'),
            //app_path('Domains/Forum')
        ],

        /*
         * Define custom_models in snake-case
         */
        'custom_models' => [
            //
        ],

        /*
         * Define excluded_models in snake-case
         */
        'excluded_models' => [
            //
        ],

        'excluded_policy_models' => [
            User::class,
        ],

        /*
         * Define any other permission here
         */
        'custom_permissions' => [
            //'view-log'
        ],

        'user_model' => User::class,

        'policies_namespace' => 'App\Policies',
    ],
];
