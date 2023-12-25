<?php

namespace App\Filament\Imports;

use App\Models\User;
use Awcodes\Shout\Components\Shout;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/user.importer.name_example'))
                ->label(__('cat/user.name')),
            ImportColumn::make('email')
                ->requiredMapping()
                ->example(__('cat/user.importer.email_example'))
                ->label(__('cat/user.email')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/user.importer.import_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/user.importer.import_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Shout::make('')
                ->color('warning')
                ->content(__('cat/user.importer.import_helper')),
        ];
    }

    public function resolveRecord(): ?User
    {
        return new User();
    }
}
