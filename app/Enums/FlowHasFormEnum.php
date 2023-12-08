<?php

namespace App\Enums;

class FlowHasFormEnum
{
    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => '草稿',
            1, 3 => '在途',
            2 => '驳回',
            4 => '通过',
        };
    }

    public static function statusColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1, 3 => 'warning',
            2 => 'danger',
            4 => 'success',
        };
    }

    public static function statusIcons(int $state): string
    {
        return match ($state) {
            0 => 'heroicon-o-ellipsis-horizontal-circle',
            1, 3 => 'heroicon-o-check-circle',
            2 => 'heroicon-o-x-circle',
            4 => 'heroicon-m-check-circle'
        };
    }
}
