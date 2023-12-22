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
            0 => __('cat/flow_has_node.status.draft'),
            1 => __('cat/flow_has_node.status.agreed'),
            2 => __('cat/flow_has_node.status.back'),
            3 => __('cat/flow_has_node.status.rejected'),
            4 => __('cat/flow_has_node.status.approved'),
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

    public static function allTypeText(): array
    {
        return [
            'user' => __('cat/menu.user'),
            'role' => __('cat/menu.role'),
        ];
    }
}
