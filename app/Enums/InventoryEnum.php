<?php

namespace App\Enums;

class InventoryEnum
{
    public static function checkText(int $state): string
    {
        return match ($state) {
            0 => __('cat.inventory_uncheck'),
            1 => __('cat.inventory_in_stock'),
            2 => __('cat.inventory_not_in_stock'),
        };
    }

    public static function allCheckText(): array
    {
        return [
            0 => __('cat.inventory_uncheck'),
            1 => __('cat.inventory_in_stock'),
            2 => __('cat.inventory_not_in_stock'),
        ];
    }
}
