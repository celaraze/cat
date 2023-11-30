<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SoftwareForm;
use App\Models\DeviceHasSoftware;
use App\Models\Software;
use App\Services\DeviceService;
use App\Services\SoftwareCategoryService;
use App\Services\SoftwareService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class SoftwareAction
{
    /**
     * 创建配件.
     *
     * @return Action
     */
    public static function createSoftware(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareForm::createOrEditSoftware())
            ->action(function (array $data) {
                try {
                    $software_service = new SoftwareService();
                    $software_service->create($data);
                    NotificationUtil::make(true, '已新增软件');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建软件分类按钮.
     *
     * @return Action
     */
    public static function createSoftwareCategory(): Action
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
                    $software_category_service = new SoftwareCategoryService();
                    $software_category_service->create($data);
                    NotificationUtil::make(true, '已创建软件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建软件-设备按钮.
     *
     * @param Model|null $out_software
     * @return Action
     */
    public static function createDeviceHasSoftware(Model $out_software = null): Action
    {
        return Action::make('附加到设备')
            ->form([
                Select::make('device_id')
                    ->options(DeviceService::pluckOptions())
                    ->searchable()
                    ->label('设备')
            ])
            ->action(function (array $data, Software $software) use ($out_software) {
                try {
                    if ($out_software) {
                        $software = $out_software;
                    }
                    $data = [
                        'device_id' => $data['device_id'],
                        'user_id' => auth()->id(),
                        'status' => '附加',
                    ];
                    $software->service()->createHasSoftware($data);
                    NotificationUtil::make(true, '软件已附加到设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 软件脱离设备按钮.
     *
     * @return Action
     */
    public static function deleteDeviceHasSoftware(): Action
    {
        return Action::make('脱离')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSoftware $device_has_software) {
                try {
                    $data = [
                        'user_id' => auth()->id(),
                        'status' => '脱离'
                    ];
                    $device_has_software->service()->delete($data);
                    NotificationUtil::make(true, '软件已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
