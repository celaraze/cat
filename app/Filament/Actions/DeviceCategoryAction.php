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
    public static function create(): Action
    {
        return Action::make(__('cat/device_category.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(DeviceCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_category_service = new DeviceCategoryService();
                    $device_category_service->create($data);
                    NotificationUtil::make(true, __('cat/device_category.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toDevice(): Action
    {
        return Action::make(__('cat/device_category.action.to_device'))
            ->icon('heroicon-s-server')
            ->url(DeviceResource::getUrl('index'));
    }

    public static function toDeviceView(): Action
    {
        return Action::make(__('cat/device_category.action.to_device_view'))
            ->icon('heroicon-s-server')
            ->url(function (Device $device) {
                return DeviceResource::getUrl('view', ['record' => $device->getKey()]);
            });
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/device_category.action.delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->slideOver()
            ->form(DeviceCategoryForm::delete())
            ->action(function (DeviceCategory $device_category) {
                try {
                    $device_category->service()->delete();
                    NotificationUtil::make(true, __('cat/device_category.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
