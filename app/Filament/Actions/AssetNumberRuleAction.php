<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SettingForm;
use App\Services\AssetNumberRuleService;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;

class AssetNumberRuleAction
{
    /**
     * 创建资产编号规则。
     */
    public static function createAssetNumberRule(): Action
    {
        return Action::make('新增规则')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SettingForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $asset_number_rule_service = new AssetNumberRuleService();
                    $asset_number_rule_service->create($data);
                    NotificationUtil::make(true, '已新增规则');
                } catch (Exception $exception) {
                    Log::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
