<?php

namespace App\Enums;

class DeviceHasUserEnum
{
    public static function statusText(int $state): string
    {
        return match ($state) {
            1 => __('cat.device_has_user.status.using'),
            2 => __('cat.device_has_user.status.borrowing'),
        };
    }

    public static function allStatusText(): array
    {
        return [
            1 => __('cat.device_has_user.status.using'),
            2 => __('cat.device_has_user.status.borrowing'),
        ];
    }
}
