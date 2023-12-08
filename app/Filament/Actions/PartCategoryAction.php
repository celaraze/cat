<?php

namespace App\Filament\Actions;

use App\Filament\Forms\PartCategoryForm;
use App\Filament\Resources\PartResource;
use App\Models\Part;
use App\Models\PartCategory;
use App\Services\PartCategoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class PartCategoryAction
{
    /**
     * 创建设备分类按钮.
     */
    public static function create(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(PartCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $part_category_service = new PartCategoryService();
                    $part_category_service->create($data);
                    NotificationUtil::make(true, '已创建配件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 删除配件分类.
     */
    public static function delete(): Action
    {
        return Action::make('删除')
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(PartCategoryForm::delete())
            ->action(function (PartCategory $part_category) {
                try {
                    $part_category->service()->delete();
                    NotificationUtil::make(true, '已删除配件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 前往配件清单.
     */
    public static function toParts(): Action
    {
        return Action::make('返回配件')
            ->icon('heroicon-m-cpu-chip')
            ->url(PartResource::getUrl('index'));
    }

    /**
     * 前往配件.
     */
    public static function toPart(): Action
    {
        return Action::make('前往配件详情')
            ->icon('heroicon-m-cpu-chip')
            ->url(function (Part $part) {
                return PartResource::getUrl('view', ['record' => $part->getKey()]);
            });
    }
}
