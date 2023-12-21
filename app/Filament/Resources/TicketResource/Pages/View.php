<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Enums\TicketEnum;
use App\Filament\Resources\TicketResource;
use App\Filament\Widgets\TicketHasTrackMinutePie;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    use QueryRecordByUrl;

    protected static string $resource = TicketResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '详情';
    }

    public static function getNavigationBadge(): ?string
    {
        return TicketEnum::statusText(self::getRecordStatus());
    }

    public static function getRecordStatus()
    {
        return self::queryRecord()->getAttribute('status') ?? 0;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return TicketEnum::statusColor(self::getRecordStatus());
    }

    protected function getFooterWidgets(): array
    {
        return [
            TicketHasTrackMinutePie::make(),
        ];
    }
}
