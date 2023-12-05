<?php

namespace App\Filament\Forms;

use App\Models\Device;
use App\Models\Part;
use App\Models\Software;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class InventoryForm
{
    /**
     * 创建盘点.
     */
    public static function create(): array
    {
        return [
            TextInput::make('name')
                ->label('任务名称')
                ->required(),
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
                    if (! $model) {
                        return [];
                    }
                    $model = new $model;

                    return $model->service()->pluckOptions();
                })
                ->hint('留空为选择全部该类资产'),
        ];
    }

    /**
     * 盘点.
     */
    public static function check(): array
    {
        return [
            Radio::make('check')
                ->options([
                    1 => '在库',
                    2 => '标记缺失',
                ])
                ->label('操作')
                ->required(),
            TextInput::make('comment')
                ->label('备忘'),
        ];
    }
}
