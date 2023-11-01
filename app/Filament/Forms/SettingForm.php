<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SettingForm
{
    /**
     * 创建或编辑资产编号规则的表单.
     *
     * @return array
     */
    public static function createOrEditSettingAssetNumber(): array
    {
        $description = '例如：PC-{year}{month}{day}-{auto-increment} ，自增长度5，实际上生成的结果为：PC-20230921-00001 。';
        return [
            TextInput::make('name')
                ->label('名称')
                ->required(),
            Textarea::make('formula')
                ->label('公式')
                ->required(),
            TextInput::make('auto_increment_length')
                ->label('自增长度')
                ->numeric()
                ->required(),
            Shout::make('description')
                ->label('公式说明')
                ->content($description)
        ];
    }
}
