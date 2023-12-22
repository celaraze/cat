<?php

namespace App\Filament\Resources\SecretResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Resources\SecretResource;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    use QueryRecordByUrl;

    protected static string $resource = SecretResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.view');
    }

    public static function getNavigationBadge(): ?string
    {
        return AssetEnum::statusText(self::getRecordStatus());
    }

    public static function getRecordStatus()
    {
        return self::queryRecord()->getAttribute('status') ?? 0;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return AssetEnum::statusColor(self::getRecordStatus());
    }
}
