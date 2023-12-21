<?php

namespace App\Filament\Forms;

use App\Models\Software;
use App\Services\AssetNumberRuleService;
use App\Services\FlowService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Ramsey\Uuid\Uuid;

class SoftwareForm
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
                ->label(__('cat.asset_number'))
                ->required(function () {
                    return ! AssetNumberRuleService::isAuto(Software::class);
                })
                ->readOnly(function () {
                    return AssetNumberRuleService::isAuto(Software::class);
                })
                ->hintAction(
                    Action::make('资产编号已绑定自动生成，无需填写本字段')
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->visible(function () {
                            return AssetNumberRuleService::isAuto(Software::class);
                        })
                ),
            //endregion

            //region 文本 名称 name
            TextInput::make('name')
                ->label(__('cat.name'))
                ->required(),
            //endregion

            //region 选择 分类 category_id
            Select::make('category_id')
                ->relationship('category', 'name')
                ->label(__('cat.category'))
                ->searchable()
                ->preload()
                ->createOptionForm(SoftwareCategoryForm::createOrEdit())
                ->required(),
            //endregion

            //region 选择 品牌 brand_id
            Select::make('brand_id')
                ->relationship('brand', 'name')
                ->label(__('cat.brand'))
                ->searchable()
                ->preload()
                ->createOptionForm(BrandForm::createOrEdit())
                ->required(),
            //endregion

            //region 文本 序列号 sn
            TextInput::make('sn')
                ->maxLength(255)
                ->label(__('cat.sn')),
            //endregion

            //region 文本 规格 specification
            TextInput::make('specification')
                ->maxLength(255)
                ->label(__('cat.specification')),
            //endregion

            //region 文本 授权数量 max_license_count
            TextInput::make('max_license_count')
                ->numeric()
                ->minValue(0)
                ->required()
                ->label(__('cat.max_license_count')),
            //endregion

            //region 上传 照片 image
            FileUpload::make('image')
                ->label(__('cat.image'))
                ->directory('software')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4().'.'.$file->getClientOriginalExtension();
                    }
                ),
            //endregion

            //region 文本 说明 description
            Textarea::make('description')
                ->label(__('cat.description')),
            //endregion

            //region 数组 额外信息 additional
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat.name')),
                    TextInput::make('text')
                        ->label(__('cat.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat.additional')),
            //endregion
        ];
    }

    /**
     * 配置报废流程.
     */
    public static function setRetireFlow(): array
    {
        return [
            Select::make('flow_id')
                ->options(FlowService::pluckOptions())
                ->required()
                ->label(__('cat.flow')),
        ];
    }

    /**
     * 配置资产编号自动生成规则.
     */
    public static function setAssetNumberRule(): array
    {
        return [
            Select::make('asset_number_rule_id')
                ->label(__('cat.asset_number_rule'))
                ->options(AssetNumberRuleService::pluckOptions())
                ->required()
                ->default(AssetNumberRuleService::getAutoRule(Software::class)?->getAttribute('id')),
            Checkbox::make('is_auto')
                ->label(__('cat.is_auto'))
                ->default(AssetNumberRuleService::getAutoRule(Software::class)?->getAttribute('is_auto')),
        ];
    }

    /**
     * 流程报废.
     */
    public static function retire(): array
    {
        return [
            TextInput::make('comment')
                ->label(__('cat.comment'))
                ->required(),
        ];
    }
}
