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
                ->example('张三')
                ->label('名称'),
            ImportColumn::make('email')
                ->requiredMapping()
                ->example('zhangsan@local.com')
                ->label('邮箱'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '你的用户导入已完成并有 '.number_format($import->successful_rows).' 行记录被导入。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).'行导入失败。';
        }

        return $body;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Shout::make('')
                ->color('warning')
                ->content('导入用户的默认密码为空且无法登录，需要正常使用账户请先重置密码。'),
        ];
    }

    public function resolveRecord(): ?User
    {
        return new User();
    }
}
