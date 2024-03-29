<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => '角色名',
    'column.guard_name' => '守卫',
    'column.roles' => '角色',
    'column.permissions' => '权限',
    'column.updated_at' => '更新时间',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => '角色名',
    'field.guard_name' => '守卫',
    'field.permissions' => '权限',
    'field.select_all.name' => '全选',
    'field.select_all.message' => '启用当前为该角色 <span class="text-primary font-medium">启用的</span> 所有权限',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => __('cat/menu.security'),
    'nav.role.label' => '角色',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => '角色',
    'resource.label.roles' => '角色',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => '实体',
    'resources' => '资源',
    'widgets' => '小组件',
    'pages' => '页面',
    'custom' => '自定义',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => '无权访问',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => '详情',
        'view_any' => '列表',
        'create' => '创建',
        'update' => '编辑',
        'delete' => '删除',
        'delete_any' => '批量删除',
        'force_delete' => '永久删除',
        'force_delete_any' => '批量永久删除',
        'restore' => '恢复',
        'reorder' => '重新排序',
        'restore_any' => '批量恢复',
        'replicate' => '复制',
        'assign_user' => '分配使用者',
        'delete_assign_user' => '解除使用者',
        'import' => '导入',
        'export' => '导出',
        'retire' => '流程报废',
        'force_retire' => '强制报废',
        'set_auto_asset_number_rule' => '配置资产编号自动生成规则',
        'reset_auto_asset_number_rule' => '重置资产编号自动生成规则',
        'create_has_part' => '附加配件',
        'delete_has_part' => '脱离配件',
        'create_has_software' => '附加软件',
        'delete_has_software' => '脱离软件',
        'check' => '盘点',
        'reset_password' => '重置密码',
        'create_has_secret' => '附加密钥',
        'delete_has_secret' => '脱离密钥',
        'view_token' => '查看密码',
        'batch_delete_has_part' => '批量脱离配件',
        'batch_delete_has_software' => '批量脱离软件',
        'batch_delete_has_secret' => '批量脱离密钥',
        'create_has_contact' => '创建联系人',
        'delete_has_contact' => '删除联系人',
        'update_has_contact' => '编辑联系人',
        'set_retire_flow' => '配置废弃流程',
        'process_flow_has_form' => '表单审批',
        'create_has_track' => '创建跟踪记录',
        'view_has_user' => '查看成员',
        'create_has_user' => '创建成员',
        'update_has_user' => '更新成员',
        'delete_has_user' => '删除成员',
        'set_assignee' => '配置处理人',
        'create_track' => '创建跟踪记录',
        'create_ticket' => '创建工单',
    ],
];
