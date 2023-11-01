<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class CommonAction
{
    /**
     * 返回列表页面.
     *
     * @param string $resource
     * @return Action
     */
    public static function back(string $resource): Action
    {
        return Action::make('返回')
            ->icon('heroicon-m-arrow-uturn-left')
            ->url(function () use ($resource) {
                /* @var Resource $resource */
                return $resource::getUrl('index');
            });
    }
}
