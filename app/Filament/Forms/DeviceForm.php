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
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Ramsey\Uuid\Uuid;

class DeviceForm
{
    /**
     * 创建工单.
     */
    public static function createTicketFromDevice(string $asset_number): array
    {
        return [
            Select::make('asset_number')
                ->label('资产编号')
                ->options(DeviceService::pluckOptions('asset_number'))
                ->searchable()
                ->preload()
                ->placeholder($asset_number)
                ->disabled(),
            TextInput::make('subject')
                ->label('主题')
                ->required(),
            RichEditor::make('description')
                ->label('描述')
                ->required(),
            Select::make('category_id')
                ->label('工单分类')
                ->options(TicketCategoryService::pluckOptions())
                ->searchable()
                ->preload()
                ->required(),
            Select::make('priority')
                ->label('优先级')
                ->options(TicketEnum::array())
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
                ->visibility('public')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4() . '.' . $file->getClientOriginalExtension();
                    }
                )
                ->image(),
            //endregion

            //region 文本 说明 description
            Textarea::make('description')
                ->label('说明'),
            //endregion
        ];
    }

    /**
     * 强制报废.
     */
    public static function forceRetire(): array
    {
        return [
            Shout::make('hint')
                ->color('danger')
                ->content('此操作将同时报废所含配件（不包含软件）'),
        ];
    }

    /**
     * 配置设备报废流程.
     */
    public static function setRetireFlow(): array
    {
        return [
            Select::make('flow_id')
                ->options(FlowService::pluckOptions())
                ->required()
                ->label('流程'),
        ];
    }

    /**
     * 配置资产编号自动生成规则.
     */
    public static function setAssetNumberRule(): array
    {
        return [
            Select::make('asset_number_rule_id')
                ->label('规则')
                ->options(AssetNumberRuleService::pluckOptions())
                ->required()
                ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('id')),
            Checkbox::make('is_auto')
                ->label('自动生成')
                ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('is_auto')),
        ];
    }

    /**
     * 流程报废.
     */
    public static function retire(): array
    {
        return [
            Shout::make('')
                ->color('danger')
                ->content('此操作将同时报废所含配件（不包含软件）'),
            TextInput::make('comment')
                ->label('说明')
                ->required(),
        ];
    }
}
