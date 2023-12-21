<?php

namespace App\Filament\Forms;

use App\Services\FlowService;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FlowHasFormForm
{
    /**
     * 审批.
     */
    public static function approve(): array
    {
        return [
            Radio::make('status')
                ->label(__('cat.flow_has_form.status'))
                ->options([
                    1 => __('cat.flow_has_form.approve'),
                    2 => __('cat.flow_has_form.back'),
                    3 => __('cat.flow_has_form.reject'),
                ])
                ->required(),
            TextInput::make('approve_comment')
                ->label(__('cat.approve_comment'))
                ->required(),
        ];
    }

    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            Select::make('flow_id')
                ->options(FlowService::pluckOptions())
                ->label(__('cat.flow'))
                ->required(),
            TextInput::make('name')
                ->label(__('cat.name'))
                ->helperText(__('cat.name_helper'))
                ->required(),
            TextInput::make('comment')
                ->label(__('cat.comment'))
                ->required(),
        ];
    }
}
