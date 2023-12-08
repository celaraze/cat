<?php

namespace App\Enums;

class AssetEnum
{
    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => '闲置',
            1 => '使用',
            2 => '借用',
            3 => '报废',
            4 => '正常',
        };
    }

    public static function allStatusText(): array
    {
        return [
            0 => '闲置',
            1 => '使用',
            2 => '借用',
            3 => '报废',
            4 => '正常',
        ];
    }

    public static function statusColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1 => 'primary',
            2 => 'warning',
            3 => 'danger',
            4 => 'success',
        };
    }

    public static function relationOperationText(int $state): string
    {
        return match ($state) {
            0 => '附加',
            1 => '脱离',
        };
    }

    public static function relationOperationColor(int $state): string
    {
        return match ($state) {
            0 => 'success',
            1 => 'danger',
        };
    }
}
