<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasSoftwareForm;
use App\Filament\Forms\SoftwareForm;
use App\Filament\Resources\SoftwareCategoryResource;
use App\Models\DeviceHasSoftware;
use App\Models\Software;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceHasSoftwareService;
use App\Services\FlowHasFormService;
use App\Services\SettingService;
use App\Services\SoftwareService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SoftwareAction
{
    public static function createDeviceHasSoftware(?Model $out_software = null): Action
    {
        /* @var Software $out_software */
        return Action::make(__('cat.action.assign_device'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSoftwareForm::createFromSoftware($out_software))
            ->action(function (array $data, Software $software) use ($out_software) {
                try {
                    if ($out_software) {
                        $software = $out_software;
                    }
                    foreach ($data['device_ids'] as $device_id) {
                        $data['device_id'] = $device_id;
                        $data['software_id'] = $software->getKey();
                        $data['creator_id'] = auth()->id();
                        $data['status'] = 0;
                        $device_has_software_service = new DeviceHasSoftwareService();
                        $device_has_software_service->create($data);
                    }
                    NotificationUtil::make(true, __('cat.action.assign_device_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $software_service = new SoftwareService();
                    $software_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteDeviceHasSoftware(): Action
    {
        return Action::make(__('cat.action.unassign_device'))
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSoftware $device_has_software) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_software->service()->delete($data);
                    NotificationUtil::make(true, __('cat.action.unassign_device_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setRetireFlow(): Action
    {
        return Action::make(__('cat.action.set_retire_flow'))
            ->slideOver()
            ->form(SoftwareForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('software_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, __('cat.action.set_retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setAssetNumberRule(): Action
    {
        return Action::make(__('cat.action.set_asset_number_rule'))
            ->slideOver()
            ->form(SoftwareForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Software::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, __('cat.action.set_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function resetAssetNumberRule(): Action
    {
        return Action::make(__('cat.action.reset_asset_number_rule'))
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Software::class);
                NotificationUtil::make(true, __('cat.action.reset_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function forceRetire(): Action
    {
        return Action::make(__('cat.action.force_retire'))
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->action(function (Software $software) {
                try {
                    $software->service()->retire();
                    NotificationUtil::make(true, __('cat.action.force_retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat.action.retire'))
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(SoftwareForm::retire())
            ->action(function (array $data, Software $software) {
                try {
                    $software_retire_flow = $software->service()->getRetireFlow();
                    $asset_number = $software->getAttribute('asset_number');
                    $flow_has_form_service = new FlowHasFormService();
                    $data['flow_id'] = $software_retire_flow->getKey();
                    $data['name'] = '软件报废单 - '.$asset_number;
                    $data['payload'] = $asset_number;
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat.action.retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toCategory(): Action
    {
        return Action::make(__('cat.action.to_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(SoftwareCategoryResource::getUrl('index'));
    }

    public static function batchDeleteDeviceHasSoftware(): BulkAction
    {
        return BulkAction::make(__('cat.action.batch_unassign'))
            ->requiresConfirmation()
            ->icon('heroicon-m-minus-circle')
            ->color('danger')
            ->action(function (Collection $device_has_software) {
                $data = [
                    'creator_id' => auth()->id(),
                    'status' => 1,
                ];
                /* @var DeviceHasSoftware $item */
                foreach ($device_has_software as $item) {
                    $item->service()->delete($data);
                }
                NotificationUtil::make(true, __('cat.action.batch_unassign_success'));
            })
            ->closeModalByClickingAway(false);
    }
}
