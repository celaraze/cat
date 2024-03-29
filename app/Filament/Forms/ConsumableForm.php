<?php

namespace App\Filament\Forms;

use App\Models\Consumable;
use App\Services\ConsumableService;
use App\Services\RoleService;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Ramsey\Uuid\Uuid;

class ConsumableForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Hidden::make('status')
                ->default(4),
            TextInput::make('name')
                ->maxLength(255)
                ->required()
                ->label(__('cat/consumable.name')),
            Select::make('category_id')
                ->label(__('cat/consumable.category_id'))
                ->relationship('category', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm(ConsumableCategoryForm::createOrEdit())
                ->required(),
            Select::make('brand_id')
                ->label(__('cat/consumable.brand_id'))
                ->relationship('brand', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm(BrandForm::createOrEdit())
                ->required(),
            Select::make('unit_id')
                ->label(__('cat/consumable.unit_id'))
                ->relationship('unit', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm(ConsumableUnitForm::createOrEdit())
                ->required(),
            TextInput::make('specification')
                ->maxLength(255)
                ->label(__('cat/consumable.specification')),
            Textarea::make('description')
                ->label(__('cat/consumable.description')),
            FileUpload::make('image')
                ->label(__('cat/consumable.image'))
                ->directory('consumables')
                ->visibility('public')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4().'.'.$file->getClientOriginalExtension();
                    }
                )
                ->image(),
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat/consumable.additional.name')),
                    TextInput::make('text')
                        ->label(__('cat/consumable.additional.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat/consumable.additional')),
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
                ->content(__('cat/consumable.form.force_retire_helper')),
        ];
    }

    /**
     * 配置耗材废弃流程.
     */
    public static function setRetireFlow(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Hidden::make('name')
                ->default('consumable_retire_flow'),
            Hidden::make('slug')
                ->default('retire_flow'),
            Hidden::make('model_name')
                ->default(Consumable::class),
            Shout::make('')
                ->color('info')
                ->content(__('cat/consumable.form.set_retire_flow_helper')),
            Repeater::make('nodes')
                ->simple(
                    Select::make('role_id')
                        ->options(RoleService::pluckOptions())
                        ->required()
                        ->label(__('cat/flow.role_id')),
                )
                ->default(function () {
                    return ConsumableService::getRetireFlow()
                        ?->nodes
                        ->pluck('role_id')
                        ->toArray() ?? [];
                })
                ->addActionLabel(__('cat/consumable.action.add_node'))
                ->hiddenLabel(),
        ];
    }

    /**
     * 流程废弃.
     */
    public static function retire(): array
    {
        return [
            Hidden::make('applicant_id')
                ->default(auth()->id()),
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Shout::make('')
                ->color('danger')
                ->content(__('cat/consumable.form.retire_helper')),
            TextInput::make('comment')
                ->label(__('cat/consumable.form.retire_comment'))
                ->required(),
        ];
    }
}
