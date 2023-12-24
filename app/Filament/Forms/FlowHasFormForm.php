<?php

namespace App\Filament\Forms;

use App\Enums\FlowHasFormEnum;
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
                ->label(__('cat/flow_has_form.status'))
                ->options(FlowHasFormEnum::allApproveText())
                ->required(),
            TextInput::make('approve_comment')
                ->label(__('cat/flow_has_form.approve_comment'))
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
                ->label(__('cat/flow_has_form.flow_id'))
                ->required(),
            TextInput::make('name')
                ->label(__('cat/flow_has_form.name'))
                ->helperText(__('cat/flow_has_form.form.name_helper'))
                ->required(),
            TextInput::make('comment')
                ->label(__('cat/flow_has_form.comment'))
                ->required(),
        ];
    }
}
