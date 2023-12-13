<?php

namespace App\Enums;

class FlowHasFormEnum
{
    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => '草稿',
            1, 2 => '在途',
            3 => '驳回',
            4 => '通过',
        };
    }

    public static function statusColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1, 2 => 'warning',
            3 => 'danger',
            4 => 'success',
        };
    }

    public static function statusIcons(int $state): string
    {
        return match ($state) {
            0 => 'heroicon-o-ellipsis-horizontal-circle',
            1, 2 => 'heroicon-o-check-circle',
            3 => 'heroicon-o-x-circle',
            4 => 'heroicon-m-check-circle'
        };
    }
}
