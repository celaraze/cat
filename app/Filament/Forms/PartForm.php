<?php

namespace App\Filament\Forms;

use App\Models\Part;
use App\Services\AssetNumberRuleService;
use App\Services\BrandService;
use App\Services\PartCategoryService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Ramsey\Uuid\Uuid;

class PartForm
{
    /**
     * 创建或者编辑设备的表单。
     */
    public static function createOrEditPart(): array
    {
        return [
            //region 文本 资产编号 asset_number
            TextInput::make('asset_number')
                ->maxLength(255)
                ->label('资产编号')
                ->required(function () {
                    return ! AssetNumberRuleService::isAuto(Part::class);
                })
                ->readOnly(function () {
                    return AssetNumberRuleService::isAuto(Part::class);
                })
                ->hintAction(
                    Action::make('资产编号已绑定自动生成，无需填写本字段')
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->visible(function () {
                            return AssetNumberRuleService::isAuto(Part::class);
                        })
                ),
            //endregion

            //region 选择 分类 category_id
            Select::make('category_id')
                ->options(PartCategoryService::pluckOptions())
                ->label('分类')
                ->searchable()
                ->required(),
            //endregion

            //region 选择 品牌 brand_id
            Select::make('brand_id')
                ->options(BrandService::pluckOptions())
                ->label('品牌')
                ->searchable()
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
                ->directory('parts')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4().'.'.$file->getClientOriginalExtension();
                    }
                ),
            //endregion
        ];
    }
}
