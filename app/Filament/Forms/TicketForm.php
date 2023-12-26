<?php

namespace App\Filament\Forms;

use App\Enums\TicketEnum;
use App\Models\Device;
use App\Services\DeviceService;
use App\Services\TicketCategoryService;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TicketForm
{
    /**
     * 创建.
     */
    public static function create($model = null): array
    {
        $form = [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            TextInput::make('subject')
                ->label(__('cat/ticket.subject'))
                ->required(),
            RichEditor::make('description')
                ->label(__('cat/ticket.description'))
                ->required(),
            Select::make('category_id')
                ->label(__('cat/ticket.category_id'))
                ->options(TicketCategoryService::pluckOptions())
                ->searchable()
                ->preload()
                ->required(),
            Select::make('priority')
                ->label(__('cat/ticket.priority'))
                ->options(TicketEnum::allPriorityText())
                ->searchable()
                ->preload()
                ->required(),
        ];

        $select_asset_number = Select::make('asset_number')
            ->label(__('cat/device.asset_number'))
            ->options(DeviceService::pluckOptions('asset_number'))
            ->searchable()
            ->required()
            ->preload();

        if ($model instanceof Device) {
            $select_asset_number
                ->placeholder($model->getAttribute('asset_number'))
                ->default($model->getAttribute('asset_number'))
                ->disabled();
        }

        array_unshift($form, $select_asset_number);

        return $form;
    }
}
