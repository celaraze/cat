<?php

namespace App\Enums;

class AssetEnum
{
    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => __('cat.asset.status.idle'),
            1 => __('cat.asset.status.using'),
            2 => __('cat.asset.status.borrowing'),
            3 => __('cat.asset.status.retired'),
            4 => __('cat.asset.status.normal'),
            5 => __('cat.asset.status.deprecated'),
        };
    }

    public static function allStatusText(): array
    {
        return [
            0 => __('cat.asset.status.idle'),
            1 => __('cat.asset.status.using'),
            2 => __('cat.asset.status.borrowing'),
            3 => __('cat.asset.status.retired'),
            4 => __('cat.asset.status.normal'),
            5 => __('cat.asset.status.deprecated'),
        ];
    }

    public static function statusColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1 => 'primary',
            2 => 'warning',
            3, 5 => 'danger',
            4 => 'success',
        };
    }

    public static function relationOperationText(int $state): string
    {
        return match ($state) {
            0 => __('cat.asset.relation_operation.attach'),
            1 => __('cat.asset.relation_operation.detach'),
        };
    }

    public static function relationOperationColor(int $state): string
    {
        return match ($state) {
            0 => 'success',
            1 => 'danger',
        };
    }

    public static function allRelationOperationText(): array
    {
        return [
            0 => __('cat.asset.relation_operation.attach'),
            1 => __('cat.asset.relation_operation.detach'),
        ];
    }
}
