<?php

namespace App\Filament\Actions;

use App\Filament\Imports\Importer;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\Action;

class ImportAction
{
    public static function make(Importer $importer): Action
    {
        return Action::make('导入')->form(
            [FileUpload::make('file')]
        )->action(function (array $data) use ($importer) {
            try {
                $importer->setPath(public_path('storage'));
                $importer->read($data['file'])->import();
                NotificationUtil::make(true, '已导入');
            } catch (Exception $exception) {
                LogUtil::error($exception);
                NotificationUtil::make(false, $exception);
            }
        })
            ->icon('heroicon-o-arrow-up-tray');
    }
}
