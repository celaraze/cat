<?php

namespace App\Utils;

use App\Filament\Components\ListRecords\Tab;

class TabUtil
{
    /**
     * 设备页顶部标签.
     *
     * @return array
     */
    public static function deviceTabs(): array
    {
        return [
            'devices' => Tab::make('设备')
                ->icon('heroicon-o-cube')
                ->url('/information/devices?from'),
            'device-categories' => Tab::make('分类')
                ->icon('heroicon-m-square-3-stack-3d')
                ->url('/information/device-categories'),
        ];
    }

    /**
     * 配件页顶部标签.
     *
     * @return array
     */
    public static function partTabs(): array
    {
        return [
            'parts' => Tab::make('配件')
                ->icon('heroicon-o-cube')
                ->url('/information/parts'),
            'part-categories' => Tab::make('分类')
                ->icon('heroicon-m-square-3-stack-3d')
                ->url('/information/part-categories'),
        ];
    }

    /**
     * 软件页顶部标签.
     *
     * @return array
     */
    public static function softwareTabs(): array
    {
        return [
            'software' => Tab::make('软件')
                ->icon('heroicon-o-cube')
                ->url('/information/software'),
            'software-categories' => Tab::make('分类')
                ->icon('heroicon-m-square-3-stack-3d')
                ->url('/information/software-categories'),
        ];
    }
}
