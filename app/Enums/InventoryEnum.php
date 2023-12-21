<?php

namespace App\Enums;

class InventoryEnum
{
    public static function checkText(int $state): string
    {
        return match ($state) {
            1 => __('cat.in_stock'),
            2 => __('cat.not_in_stock'),
        };
    }

    public static function allCheckText(): array
    {
        return [
            1 => __('cat.in_stock'),
            2 => __('cat.not_in_stock'),
        ];
    }
}
