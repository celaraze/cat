<?php

namespace App\Enums;

class FlowHasFormEnum
{
    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => __('cat.flow_has_form.status.draft'),
            1, 2 => __('cat.flow_has_form.status.processing'),
            3 => __('cat.flow_has_form.status.rejected'),
            4 => __('cat.flow_has_form.status.approved'),
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

    public static function allApproveText(): array
    {
        return [
            1 => __('cat.flow_has_form.action.status.approve'),
            2 => __('cat.flow_has_form.action.status.back'),
            3 => __('cat.flow_has_form.action.status.reject'),
        ];
    }
}
