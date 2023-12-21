<?php

namespace App\Filament\Forms;

use App\Enums\TicketEnum;
use App\Models\Device;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceService;
use App\Services\FlowService;
use App\Services\TicketCategoryService;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Ramsey\Uuid\Uuid;

class DeviceForm
{
    /**
     * 从设备创建工单.
     */
    public static function createTicketFromDevice(string $asset_number): array
    {
        return [
            Select::make('asset_number')
                ->label(__('cat.asset_number'))
                ->options(DeviceService::pluckOptions('asset_number'))
                ->searchable()
                ->preload()
                ->placeholder($asset_number)
                ->disabled(),
            TextInput::make('subject')
                ->label(__('cat.subject'))
                ->required(),
            RichEditor::make('description')
                ->label(__('cat.description'))
                ->required(),
            Select::make('category_id')
                ->label(__('cat.category'))
                ->options(TicketCategoryService::pluckOptions())
                ->searchable()
                ->preload()
                ->required(),
            Select::make('priority')
                ->label(__('cat.priority'))
                ->options(TicketEnum::allPriorityText())
                ->searchable()
                ->preload()
                ->required(),
        ];
    }

    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('asset_number')
                ->maxLength(255)
                ->label(__('cat.asset_number'))
                ->required(function () {
                    return ! AssetNumberRuleService::isAuto(Device::class);
                })
                ->readOnly(function () {
                    return AssetNumberRuleService::isAuto(Device::class);
                })
                ->hintAction(
                    Action::make(__('cat.form.create_asset_number_helper'))
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->visible(function () {
                            return AssetNumberRuleService::isAuto(Device::class);
                        })
                ),
            Select::make('category_id')
                ->relationship('category', 'name')
                ->label(__('cat.category'))
                ->preload()
                ->searchable()
                ->createOptionForm(DeviceCategoryForm::createOrEdit())
                ->required(),
            TextInput::make('name')
                ->maxLength(255)
                ->label(__('cat.name')),
            Select::make('brand_id')
                ->label(__('cat.brand'))
                ->relationship('brand', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm(BrandForm::createOrEdit())
                ->required(),
            TextInput::make('sn')
                ->maxLength(255)
                ->label(__('cat.sn')),
            TextInput::make('specification')
                ->maxLength(255)
                ->label(__('cat.specification')),
            FileUpload::make('image')
                ->label(__('cat.image'))
                ->directory('devices')
                ->visibility('public')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4().'.'.$file->getClientOriginalExtension();
                    }
                )
                ->image(),
            Textarea::make('description')
                ->label(__('cat.description')),
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat.name')),
                    TextInput::make('text')
                        ->label(__('cat.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat.additional')),
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
                ->content(__('cat.form.force_retire_device_helper')),
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
                ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('id')),
            Checkbox::make('is_auto')
                ->label(__('cat.is_auto'))
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
                ->content(__('cat.form.retire_device_helper')),
            TextInput::make('comment')
                ->label(__('cat.comment'))
                ->required(),
        ];
    }
}
