<?php

namespace App\Enums;

class FootprintEnum
{
    public static function allActionText(): array
    {
        return [
            'create' => '创建',
            'update' => '更新',
            'delete' => '删除',
            'restore' => '恢复',
            'force_delete' => '永久删除',
        ];
    }

    public static function actionText(string $state): string
    {
        return match ($state) {
            'create' => '创建',
            'update' => '更新',
            'delete' => '删除',
            'restore' => '恢复',
            'force_delete' => '永久删除',
        };
    }

    public static function actionColor(string $state): string
    {
        return match ($state) {
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'restore' => 'blue',
            'force_delete' => 'pink',
        };
    }
}
