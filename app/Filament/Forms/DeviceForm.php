<?php

namespace App\Filament\Forms;

use App\Models\Device;
use App\Services\AssetNumberRuleService;
use App\Services\PartService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Ramsey\Uuid\Uuid;

class DeviceForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            //region 文本 资产编号 asset_number
            TextInput::make('asset_number')
                ->maxLength(255)
                ->label('资产编号')
                ->required(function () {
                    return !AssetNumberRuleService::isAuto(Device::class);
                })
                ->readOnly(function () {
                    return AssetNumberRuleService::isAuto(Device::class);
                })
                ->hintAction(
                    Action::make('资产编号已绑定自动生成，无需填写本字段')
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->visible(function () {
                            return AssetNumberRuleService::isAuto(Device::class);
                        })
                ),
            //endregion

            //region 选择 分类 category_id
            Select::make('category_id')
                ->relationship('category', 'name')
                ->label('分类')
                ->preload()
                ->searchable()
                ->createOptionForm(DeviceCategoryForm::createOrEdit())
                ->required(),
            //endregion

            //region 文本 名称 name
            TextInput::make('name')
                ->maxLength(255)
                ->label('名称'),
            //endregion

            //region 选择 品牌 brand_id
            Select::make('brand_id')
                ->label('品牌')
                ->relationship('brand', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm(BrandForm::createOrEdit())
                ->required(),
            //endregion

            //region 文本 序列号 sn
            TextInput::make('sn')
                ->maxLength(255)
                ->label('序列号'),
            //endregion

            //region 文本 规格 specification
            TextInput::make('specification')
                ->maxLength(255)
                ->label('规格'),
            //endregion

            //region 上传 照片 image
            FileUpload::make('image')
                ->label('照片')
                ->directory('devices')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4() . '.' . $file->getClientOriginalExtension();
                    }
                ),
            //endregion
        ];
    }

    /**
     * 创建设备配件.
     *
     * @return array
     */
    public static function createHasPart(): array
    {
        return [
            //region 选择 配件 part_id
            Select::make('part_id')
                ->label('配件')
                ->options(PartService::pluckOptions())
                ->searchable()
                ->preload()
                ->required(),
            //endregion
        ];
    }
}
