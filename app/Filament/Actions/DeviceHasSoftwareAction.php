<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasSoftwareForm;
use App\Models\Device;
use App\Models\DeviceHasSoftware;
use App\Models\Software;
use App\Services\DeviceHasSoftwareService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DeviceHasSoftwareAction
{
    public static function batchDelete(): BulkAction
    {
        return BulkAction::make(__('cat/device_has_software.action.batch_delete'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-minus-circle')
            ->action(function (Collection $device_has_software) {
                $data = [
                    'creator_id' => auth()->id(),
                    'status' => 1,
                ];
                /* @var DeviceHasSoftware $item */
                foreach ($device_has_software as $item) {
                    $item->service()->delete($data);
                }
                NotificationUtil::make(true, __('cat/device_has_software.action.batch_delete_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/device_has_software.action.delete'))
            ->icon('heroicon-s-minus-circle')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSoftware $device_has_software) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_software->service()->delete($data);
                    NotificationUtil::make(true, __('cat/device_has_software.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function createFromSoftware(?Model $out_software = null): Action
    {
        /* @var Software $out_software */
        return Action::make(__('cat/device_has_software.action.create_from_software'))
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
                    NotificationUtil::make(true, __('cat/device_has_software.action.create_from_software_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(?Model $out_device = null): Action
    {
        return Action::make(__('cat/device_has_software.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSoftwareForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    foreach ($data['software_ids'] as $software_id) {
                        $data['software_id'] = $software_id;
                        $data['device_id'] = $device->getKey();
                        $data['creator_id'] = auth()->id();
                        $data['status'] = 0;
                        $device_has_software_service = new DeviceHasSoftwareService();
                        $device_has_software_service->create($data);
                    }
                    NotificationUtil::make(true, __('cat/device_has_software.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteFromSoftware(): Action
    {
        return Action::make(__('cat/device_has_software_action.delete_from_software'))
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSoftware $device_has_software) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_software->service()->delete($data);
                    NotificationUtil::make(true, __('cat/device_has_software_action.delete_from_software_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function batchDeleteFromSoftware(): BulkAction
    {
        return BulkAction::make(__('cat/device_has_software.action.batch_delete_from_software'))
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
                NotificationUtil::make(true, __('cat/device_has_software.action.batch_delete_from_software_success'));
            })
            ->closeModalByClickingAway(false);
    }
}
