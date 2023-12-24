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

class DeviceHasSoftwareAction
{
    public static function batchDelete(): BulkAction
    {
        return BulkAction::make(__('cat/device_has_software.action.batch_delete'))
            ->slideOver()
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
            ->slideOver()
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

    public static function create($model): Action
    {
        return Action::make(__('cat/device_has_software.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSoftwareForm::create($model))
            ->action(function (array $data) use ($model): void {
                try {
                    // 如果在设备页面创建，则获取设备 id
                    if ($model instanceof Device) {
                        $data['device_id'] = $model->getKey();
                    }
                    // 如果在软件页面创建，则获取软件 id，并创建一个单元素数组
                    // 因为下面处理时需要使用 foreach 循环，默认批量处理
                    if ($model instanceof Software) {
                        $data['software_ids'] = [$model->getKey()];
                    }
                    foreach ($data['software_ids'] as $software_id) {
                        $data['software_id'] = $software_id;
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
}
