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
                ->label(__('cat.name'))
                ->required(),
            Select::make('class_name')
                ->options([
                    Device::class => '设备',
                    Part::class => '配件',
                    Software::class => '软件',
                ])
                ->label(__('cat.asset'))
                ->reactive()
                ->required(),
            Select::make('model_ids')
                ->multiple()
                ->searchable()
                ->label(__('cat.asset_number'))
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
                    1 => __('cat.in_stock'),
                    2 => __('cat.not_in_stock'),
                ])
                ->label(__('cat.operation'))
                ->required(),
            TextInput::make('comment')
                ->label(__('cat.comment')),
        ];
    }
}
