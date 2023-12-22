<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasPartForm;
use App\Models\Device;
use App\Models\DeviceHasPart;
use App\Models\Part;
use App\Services\DeviceHasPartService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DeviceHasPartAction
{
    public static function createFromPart(?Model $out_part = null): Action
    {
        /* @var Part $out_part */
        return Action::make(__('cat/device_has_part.action.create_from_part'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasPartForm::createFromPart($out_part))
            ->action(function (array $data, Part $part) use ($out_part) {
                try {
                    if ($out_part) {
                        $part = $out_part;
                    }
                    $data['part_id'] = $part->getKey();
                    $data['creator_id'] = auth()->id();
                    $data['status'] = 0;
                    $device_has_part_service = new DeviceHasPartService();
                    $device_has_part_service->create($data);
                    NotificationUtil::make(true, __('cat/device_has_part.action.create_from_part_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(?Model $out_device = null): Action
    {
        return Action::make(__('cat/device_has_part.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasPartForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    foreach ($data['part_ids'] as $part_id) {
                        $data['part_id'] = $part_id;
                        $data['device_id'] = $device->getKey();
                        $data['creator_id'] = auth()->id();
                        $data['status'] = 0;
                        $device_has_part_service = new DeviceHasPartService();
                        $device_has_part_service->create($data);
                    }
                    NotificationUtil::make(true, __('cat/device_has_part.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteFromPart(): Action
    {
        return Action::make(__('cat/device_has_part.action.delete_from_part'))
            ->icon('heroicon-s-minus-circle')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasPart $device_has_part) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_part->service()->delete($data);
                    NotificationUtil::make(true, __('cat/device_has_part.action.delete_from_part_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/device_has_part.action.delete'))
            ->icon('heroicon-s-minus-circle')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasPart $device_has_part) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_part->service()->delete($data);
                    NotificationUtil::make(true, __('cat/device_has_part.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function batchDelete(): BulkAction
    {
        return BulkAction::make(__('cat/device_has_part.action.batch_delete'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-minus-circle')
            ->action(function (Collection $device_has_parts) {
                $data = [
                    'creator_id' => auth()->id(),
                    'status' => 1,
                ];
                /* @var DeviceHasPart $device_has_part */
                foreach ($device_has_parts as $device_has_part) {
                    $device_has_part->service()->delete($data);
                }
                NotificationUtil::make(true, __('cat/device_has_part.action.batch_delete_success'));
            })
            ->closeModalByClickingAway(false);
    }
}
