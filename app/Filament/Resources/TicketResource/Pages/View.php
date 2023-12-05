<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Actions\CommonAction;
use App\Filament\Actions\TicketAction;
use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 标记完成
            TicketAction::finish($this->record)
                ->visible(function () {
                    return $this->record->getAttribute('user_id') == auth()->id();
                }),
            // 返回列表
            CommonAction::back($this->getResource()),
        ];
    }
}
