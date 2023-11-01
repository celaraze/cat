<?php

namespace App\Filament\Resources\Information\BrandResource\Pages;

use App\Filament\Resources\Information\BrandResource;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = BrandResource::class;

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
