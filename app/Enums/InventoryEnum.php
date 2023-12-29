<?php

namespace App\Enums;

class InventoryEnum
{
    public static function checkText(int $state): string
    {
        return match ($state) {
            0 => __('cat/inventory_has_track.uncheck'),
            1 => __('cat/inventory_has_track.in_stock'),
            2 => __('cat/inventory_has_track.not_in_stock'),
        };
    }

    public static function allCheckText(?int $unset = null): array
    {
        $options = [
            0 => __('cat/inventory_has_track.uncheck'),
            1 => __('cat/inventory_has_track.in_stock'),
            2 => __('cat/inventory_has_track.not_in_stock'),
        ];
        unset($options[$unset]);

        return $options;
    }
}
