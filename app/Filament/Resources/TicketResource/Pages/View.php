<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Actions\TicketAction;
use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }

    protected function getHeaderActions(): array
    {
        return [
            // 标记完成
            TicketAction::finish($this->record)
                ->visible(function () {
                    return $this->record->getAttribute('user_id') == auth()->id();
                }),
        ];
    }
}
