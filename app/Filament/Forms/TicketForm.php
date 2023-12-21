<?php

namespace App\Filament\Forms;

use App\Enums\TicketEnum;
use App\Services\DeviceService;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TicketForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            Select::make('asset_number')
                ->label(__('cat.asset_number'))
                ->options(DeviceService::pluckOptions('asset_number'))
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('subject')
                ->label(__('cat.subject'))
                ->required(),
            RichEditor::make('description')
                ->label(__('cat.description'))
                ->required(),
            Select::make('category_id')
                ->label(__('cat.category'))
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->createOptionForm(TicketCategoryForm::createOrEdit())
                ->required(),
            Select::make('priority')
                ->label(__('cat.priority'))
                ->options(TicketEnum::allPriorityText())
                ->searchable()
                ->preload()
                ->required(),
        ];
    }
}
