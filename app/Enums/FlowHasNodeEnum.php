<?php

namespace App\Enums;

class FlowHasNodeEnum
{
    public static function statusIcons(int $state): string
    {
        return match ($state) {
            0 => 'heroicon-o-ellipsis-horizontal-circle',
            1 => 'heroicon-o-check-circle',
            2 => 'heroicon-o-arrow-left-circle',
            3 => 'heroicon-o-x-circle',
            4 => 'heroicon-m-check-circle'
        };
    }

    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => '草稿',
            1 => '同意',
            2 => '退回',
            3 => '驳回',
            4 => '通过',
        };
    }

    public static function statusColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1, 4 => 'success',
            2 => 'warning',
            3 => 'danger'
        };
    }
}
