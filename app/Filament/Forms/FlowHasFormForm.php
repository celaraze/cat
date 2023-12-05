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
                ->label('审批类型')
                ->options([
                    1 => '同意，表单进入下一个审核。',
                    2 => '退回，表单回到上一个审核。',
                    3 => '驳回，表单直接结束流程。',
                ])
                ->required(),
            TextInput::make('approve_comment')
                ->label('审批意见')
                ->required(),
        ];
    }

    /**
     * 创建表单.
     */
    public static function create(): array
    {
        return [
            Select::make('flow_id')
                ->options(FlowService::pluckOptions())
                ->label('选择流程')
                ->required(),
            TextInput::make('name')
                ->label('表单名称')
                ->helperText('申请表单的主题，例如某某某申请某资产一台。')
                ->required(),
            TextInput::make('comment')
                ->label('申请意见')
                ->required(),
        ];
    }
}
