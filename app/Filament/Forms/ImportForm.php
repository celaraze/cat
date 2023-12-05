<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;

class ImportForm
{
    /**
     * 导入.
     */
    public static function import(): array
    {
        return [
            FileUpload::make('file'),
        ];
    }
}
