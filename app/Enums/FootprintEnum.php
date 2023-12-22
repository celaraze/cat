<?php

namespace App\Enums;

class FootprintEnum
{
    public static function allActionText(): array
    {
        return [
            'create' => __('cat/footprint.action.create'),
            'update' => __('cat/footprint.action.update'),
            'delete' => __('cat/footprint.action.delete'),
            'restore' => __('cat/footprint.action.restore'),
            'force_delete' => __('cat/footprint.action.force_delete'),
        ];
    }

    public static function actionText(string $state): string
    {
        return match ($state) {
            'create' => __('cat/footprint.action.create'),
            'update' => __('cat/footprint.action.update'),
            'delete' => __('cat/footprint.action.delete'),
            'restore' => __('cat/footprint.action.restore'),
            'force_delete' => __('cat/footprint.action.force_delete'),
        };
    }

    public static function actionColor(string $state): string
    {
        return match ($state) {
            'create' => 'success',
            'update' => 'warning',
            'delete', 'force_delete' => 'danger',
            'restore' => 'blue',
        };
    }
}
