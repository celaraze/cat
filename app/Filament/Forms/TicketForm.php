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
                ->label('资产编号')
                ->options(DeviceService::pluckOptions('asset_number'))
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('subject')
                ->label('主题')
                ->required(),
            RichEditor::make('description')
                ->label('描述')
                ->required(),
            Select::make('category_id')
                ->label('工单分类')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->createOptionForm(TicketCategoryForm::createOrEdit())
                ->required(),
            Select::make('priority')
                ->label('优先级')
                ->options(TicketEnum::allPriorityText())
                ->searchable()
                ->preload()
                ->required(),
        ];
    }
}
