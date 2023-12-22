<?php

namespace App\Filament\Forms;

use App\Enums\AssetEnum;
use App\Enums\InventoryEnum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class InventoryForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            TextInput::make('name')
                ->label(__('cat.inventory.name'))
                ->required(),
            Select::make('class_name')
                ->options(AssetEnum::allAssetTypeText())
                ->label(__('cat.inventory.class_name'))
                ->reactive()
                ->required(),
            Select::make('model_ids')
                ->multiple()
                ->searchable()
                ->label(__('cat.inventory.model_ids'))
                ->options(function (callable $get) {
                    $model = $get('class_name');
                    if (! $model) {
                        return [];
                    }
                    $model = new $model;

                    return $model->service()->pluckOptions();
                })
                ->hint(__('cat.form.create_model_ids_helper')),
        ];
    }

    /**
     * 盘点.
     */
    public static function check(): array
    {
        return [
            Radio::make('check')
                ->options(InventoryEnum::allCheckText())
                ->label(__('cat.inventory.check'))
                ->required(),
            TextInput::make('comment')
                ->label(__('cat.inventory.comment')),
        ];
    }
}
