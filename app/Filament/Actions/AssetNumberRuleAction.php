<?php

namespace App\Filament\Actions;

use App\Filament\Forms\AssetNumberRuleForm;
use App\Services\AssetNumberRuleService;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;

class AssetNumberRuleAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/asset_number_rule.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(AssetNumberRuleForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $asset_number_rule_service = new AssetNumberRuleService();
                    $asset_number_rule_service->create($data);
                    NotificationUtil::make(true, __('cat/asset_number_rule.action.create_success'));
                } catch (Exception $exception) {
                    Log::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
