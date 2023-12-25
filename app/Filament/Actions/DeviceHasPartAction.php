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

class DeviceHasPartAction
{
    public static function create($model): Action
    {
        return Action::make(__('cat/device_has_part.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasPartForm::create($model))
            ->action(function (array $data) use ($model): void {
                try {
                    // 如果在设备页面创建，则获取设备 id
                    if ($model instanceof Device) {
                        $data['device_id'] = $model->getKey();
                    }
                    // 如果在配件页面创建，则获取配件 id，并创建一个单元素数组
                    // 因为下面处理时需要使用 foreach 循环，默认批量处理
                    if ($model instanceof Part) {
                        $data['part_ids'] = [$model->getKey()];
                    }
                    foreach ($data['part_ids'] as $part_id) {
                        $data['part_id'] = $part_id;
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

    public static function batchDelete(): BulkAction
    {
        return BulkAction::make(__('cat/device_has_part.action.batch_delete'))
            ->slideOver()
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

    public static function delete(): Action
    {
        return Action::make(__('cat/device_has_part.action.delete'))
            ->slideOver()
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
}
