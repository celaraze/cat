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
            TextInput::make('asset_number')
                ->maxLength(255)
                ->label(__('cat/software.asset_number'))
                ->required(function () {
                    return ! AssetNumberRuleService::isAuto(Software::class);
                })
                ->readOnly(function () {
                    return AssetNumberRuleService::isAuto(Software::class);
                })
                ->hintAction(
                    Action::make(__('cat/software.action.asset_number.create_helper'))
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->visible(function () {
                            return AssetNumberRuleService::isAuto(Software::class);
                        })
                ),
            TextInput::make('name')
                ->label(__('cat/software.name'))
                ->required(),
            Select::make('category_id')
                ->relationship('category', 'name')
                ->label(__('cat/software.category_id'))
                ->searchable()
                ->preload()
                ->createOptionForm(SoftwareCategoryForm::createOrEdit())
                ->required(),
            Select::make('brand_id')
                ->relationship('brand', 'name')
                ->label(__('cat/software.brand_id'))
                ->searchable()
                ->preload()
                ->createOptionForm(BrandForm::createOrEdit())
                ->required(),
            TextInput::make('sn')
                ->maxLength(255)
                ->label(__('cat/software.sn')),
            TextInput::make('specification')
                ->maxLength(255)
                ->label(__('cat/software.specification')),
            TextInput::make('max_license_count')
                ->numeric()
                ->minValue(0)
                ->required()
                ->label(__('cat/software.max_license_count')),
            FileUpload::make('image')
                ->label(__('cat/software.image'))
                ->directory('software')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4().'.'.$file->getClientOriginalExtension();
                    }
                ),
            Textarea::make('description')
                ->label(__('cat/software.description')),
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat/software.additional.name')),
                    TextInput::make('text')
                        ->label(__('cat/software.additional.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat/software.additional')),
        ];
    }

    /**
     * 配置废弃流程.
     */
    public static function setRetireFlow(): array
    {
        return [
            Select::make('flow_id')
                ->options(FlowService::pluckOptions())
                ->required()
                ->label(__('cat/software.flow_id')),
        ];
    }

    /**
     * 配置资产编号自动生成规则.
     */
    public static function setAssetNumberRule(): array
    {
        return [
            Select::make('asset_number_rule_id')
                ->label(__('cat/software.asset_number_rule_id'))
                ->options(AssetNumberRuleService::pluckOptions())
                ->required()
                ->default(AssetNumberRuleService::getAutoRule(Software::class)?->getAttribute('id')),
            Checkbox::make('is_auto')
                ->label(__('cat/software.is_auto'))
                ->default(AssetNumberRuleService::getAutoRule(Software::class)?->getAttribute('is_auto')),
        ];
    }

    /**
     * 流程废弃.
     */
    public static function retire(): array
    {
        return [
            TextInput::make('comment')
                ->label(__('cat/software.form.retire_comment'))
                ->required(),
        ];
    }
}
