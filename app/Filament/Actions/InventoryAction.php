<?php

namespace App\Filament\Actions;

use App\Filament\Resources\InventoryResource;
use App\Models\Information\Device;
use App\Models\Information\Part;
use App\Models\Information\Software;
use App\Models\Inventory;
use App\Models\InventoryHasTrack;
use App\Services\InventoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;

class InventoryAction
{
    /**
     * 创建盘点.
     *
     * @return Action
     */
    public static function createInventory(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form([
                TextInput::make('name')
                    ->label('任务名称')
                    ->required()
                ,
                Select::make('class_name')
                    ->options([
                        Device::class => '设备',
                        Part::class => '配件',
                        Software::class => '软件',
                    ])
                    ->label('资产')
                    ->reactive()
                    ->required(),
                Select::make('model_ids')
                    ->multiple()
                    ->searchable()
                    ->label('资产编号')
                    ->options(function (callable $get) {
                        $model = $get('class_name');
                        if (!$model) {
                            return [];
                        }
                        $model = new $model;
                        return $model->service()->pluckOptions();
                    })
                    ->hint('留空为选择全部该类资产'),
            ])
            ->action(function (array $data) {
                try {
                    $inventory_service = new InventoryService();
                    $inventory_service->create($data);
                    NotificationUtil::make(true, '已创建盘点');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 删除盘点.
     *
     * @return \Filament\Actions\Action
     */
    public static function deleteInventory(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('放弃')
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (Inventory $inventory) {
                try {
                    $inventory->service()->delete();
                    NotificationUtil::make(true, '已放弃盘点');
                    redirect(InventoryResource::getUrl('index'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 盘点.
     *
     * @return Action
     */
    public static function check(): Action
    {
        return Action::make('盘点')
            ->icon('heroicon-m-document-check')
            ->form([
                Radio::make('check')
                    ->options([
                        1 => '在库',
                        2 => '标记缺失',
                    ])
                    ->label('操作')
                    ->required(),
                TextInput::make('comment')
                    ->label('备忘')
            ])
            ->action(function (array $data, InventoryHasTrack $inventory_has_track) {
                try {
                    $inventory_has_track->service()->check($data);
                    NotificationUtil::make(true, '已盘点');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
