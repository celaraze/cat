<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\TextInput;

class TicketCategoryForm
{
    /**
     * 创建或更新.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->label(__('cat.name'))
                ->required(),
        ];
    }

    /**
     * 删除.
     */
    public static function delete(): array
    {
        return [
            Shout::make('hint')
                ->color('danger')
                ->content(__('cat.form.delete_ticket_category_helper')),
        ];
    }
}
