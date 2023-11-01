<?php

namespace App\Filament\Actions\Imformation;

use App\Filament\Forms\PartForm;
use App\Models\Information\DeviceHasPart;
use App\Models\Information\Part;
use App\Services\Information\DeviceService;
use App\Services\Information\PartCategoryService;
use App\Services\Information\PartService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PartAction
{
    /**
     * 创建设备分类按钮.
     *
     * @return Action
     */
    public static function createPartCategory(): Action
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
                    $part_category_service = new PartCategoryService();
                    $part_category_service->create($data);
                    NotificationUtil::make(true, '已创建配件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建配件.
     *
     * @return Action
     */
    public static function createPart(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(PartForm::createOrEditPart())
            ->action(function (array $data) {
                try {
                    $device_service = new PartService();
                    $device_service->create($data);
                    NotificationUtil::make(true, '已新增配件');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建配件-设备按钮.
     *
     * @param Model|null $out_part
     * @return Action
     */
    public static function createDeviceHasPart(Model $out_part = null): Action
    {
        return Action::make('附加到设备')
            ->form([
                Select::make('device_id')
                    ->options(DeviceService::pluckOptions())
                    ->searchable()
                    ->label('设备')
            ])
            ->action(function (array $data, Part $part) use ($out_part) {
                try {
                    if ($out_part) {
                        $part = $out_part;
                    }
                    $data = [
                        'device_id' => $data['device_id'],
                        'user_id' => auth()->id(),
                        'status' => '附加',
                    ];
                    $part->service()->createHasPart($data);
                    NotificationUtil::make(true, '配件已附加到设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 配件脱离设备按钮.
     *
     * @return Action
     */
    public static function deleteDeviceHasPart(): Action
    {
        return Action::make('脱离')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasPart $device_has_part) {
                try {
                    $data = [
                        'user_id' => auth()->id(),
                        'status' => '脱离'
                    ];
                    $device_has_part->service()->delete($data);
                    NotificationUtil::make(true, '配件已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
