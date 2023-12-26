<?php

namespace App\Filament\Forms;

use App\Models\Device;
use App\Services\AssetNumberRuleService;
use App\Services\FlowService;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
            Hidden::make('creator_id')
                ->default(auth()->id()),
            TextInput::make('asset_number')
                ->maxLength(255)
                ->label(__('cat/device.asset_number'))
                ->required(function () {
                    return ! AssetNumberRuleService::isAuto(Device::class);
                })
                ->readOnly(function () {
                    return AssetNumberRuleService::isAuto(Device::class);
                })
                ->hintAction(
                    Action::make(__('cat/device.form.asset_number.create_helper'))
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->visible(function () {
                            return AssetNumberRuleService::isAuto(Device::class);
                        })
                ),
            Select::make('category_id')
                ->relationship('category', 'name')
                ->label(__('cat/device.category_id'))
                ->preload()
                ->searchable()
                ->createOptionForm(DeviceCategoryForm::createOrEdit())
                ->required(),
            TextInput::make('name')
                ->maxLength(255)
                ->label(__('cat/device.name')),
            Select::make('brand_id')
                ->label(__('cat/device.brand_id'))
                ->relationship('brand', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm(BrandForm::createOrEdit())
                ->required(),
            TextInput::make('sn')
                ->maxLength(255)
                ->label(__('cat/device.sn')),
            TextInput::make('specification')
                ->maxLength(255)
                ->label(__('cat/device.specification')),
            FileUpload::make('image')
                ->label(__('cat/device.image'))
                ->directory('devices')
                ->visibility('public')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4().'.'.$file->getClientOriginalExtension();
                    }
                )
                ->image(),
            Textarea::make('description')
                ->label(__('cat/device.description')),
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat/device.additional.name')),
                    TextInput::make('text')
                        ->label(__('cat/device.additional.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat/device.additional')),
        ];
    }

    /**
     * 强制废弃.
     */
    public static function forceRetire(): array
    {
        return [
            Shout::make('hint')
                ->color('danger')
                ->content(__('cat/device.form.force_retire_helper')),
        ];
    }

    /**
     * 配置设备废弃流程.
     */
    public static function setRetireFlow(): array
    {
        return [
            Select::make('flow_id')
                ->options(FlowService::pluckOptions())
                ->required()
                ->label(__('cat/device.flow_id')),
        ];
    }

    /**
     * 配置资产编号自动生成规则.
     */
    public static function setAssetNumberRule(): array
    {
        return [
            Select::make('asset_number_rule_id')
                ->label(__('cat/device.asset_number_rule_id'))
                ->options(AssetNumberRuleService::pluckOptions())
                ->required()
                ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('id')),
            Checkbox::make('is_auto')
                ->label(__('cat/device.is_auto'))
                ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('is_auto')),
        ];
    }

    /**
     * 流程废弃.
     */
    public static function retire(): array
    {
        return [
            Shout::make('')
                ->color('danger')
                ->content(__('cat/device.form.retire_helper')),
            TextInput::make('comment')
                ->label(__('cat/device.form.retire_comment'))
                ->required(),
        ];
    }
}
