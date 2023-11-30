<?php

namespace App\Filament\Actions;

use App\Services\BrandService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;

class BrandAction
{
    /**
     * 创建品牌.
     *
     * @return Action
     */
    public static function createBrand(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form([
                TextInput::make('name')
                    ->label('名称')
                    ->required(),
            ])
            ->action(function (array $data) {
                try {
                    $brand_service = new BrandService();
                    $brand_service->create($data);
                    NotificationUtil::make(true, '已创建品牌');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
