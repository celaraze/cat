<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceCategoryForm;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceCategory;
use App\Services\DeviceCategoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class DeviceCategoryAction
{
    /**
     * 创建设备分类.
     */
    public static function create(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(DeviceCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_category_service = new DeviceCategoryService();
                    $device_category_service->create($data);
                    NotificationUtil::make(true, '已创建设备分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 前往设备清单.
     */
    public static function toDevices(): Action
    {
        return Action::make('返回设备')
            ->icon('heroicon-s-server')
            ->url(DeviceResource::getUrl('index'));
    }

    /**
     * 前往设备.
     */
    public static function toDevice(): Action
    {
        return Action::make('前往设备详情')
            ->icon('heroicon-s-server')
            ->url(function (Device $device) {
                return DeviceResource::getUrl('view', ['record' => $device->getKey()]);
            });
    }

    /**
     * 删除设备分类.
     */
    public static function delete(): Action
    {
        return Action::make('删除')
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(DeviceCategoryForm::delete())
            ->action(function (DeviceCategory $device_category) {
                try {
                    $device_category->service()->delete();
                    NotificationUtil::make(true, '已删除设备分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
