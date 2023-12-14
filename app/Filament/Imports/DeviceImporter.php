<?php

namespace App\Filament\Imports;

use App\Models\Device;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DeviceImporter extends Importer
{
    protected static ?string $model = Device::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('asset_number')
                ->requiredMapping()
                ->example('Example001')
                ->label('资产编号'),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example('台式机')
                ->label('分类'),
            ImportColumn::make('name')
                ->example('工作站 A 组 1 号机')
                ->label('名称'),
            ImportColumn::make('sn')
                ->example('AAAAAAA')
                ->label('序列号'),
            ImportColumn::make('specification')
                ->example('1U 2C 4GB')
                ->label('规格'),
            ImportColumn::make('image')
                ->example('https://test.com/logo.png')
                ->label('照片'),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example('微软 Microsoft')
                ->label('品牌'),
            ImportColumn::make('description')
                ->example('这是一台工作站')
                ->label('说明'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '你的设备导入已完成并有 '.number_format($import->successful_rows).' 行记录被导入。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).'行导入失败。';
        }

        return $body;
    }

    public function resolveRecord(): ?Device
    {
        // return Device::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Device();
    }
}
