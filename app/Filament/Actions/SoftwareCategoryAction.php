<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SoftwareCategoryForm;
use App\Filament\Resources\SoftwareResource;
use App\Models\Software;
use App\Models\SoftwareCategory;
use App\Services\SoftwareCategoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class SoftwareCategoryAction
{
    /**
     * 创建软件分类按钮.
     */
    public static function create(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $software_category_service = new SoftwareCategoryService();
                    $software_category_service->create($data);
                    NotificationUtil::make(true, '已创建软件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 删除软件分类.
     */
    public static function delete(): Action
    {
        return Action::make('删除')
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(SoftwareCategoryForm::delete())
            ->action(function (SoftwareCategory $software_category) {
                try {
                    $software_category->service()->delete();
                    NotificationUtil::make(true, '已删除软件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 前往软件清单.
     */
    public static function toSoftwareIndex(): Action
    {
        return Action::make('返回软件')
            ->icon('heroicon-m-squares-plus')
            ->url(SoftwareResource::getUrl('index'));
    }

    /**
     * 前往软件.
     */
    public static function toSoftware(): Action
    {
        return Action::make('前往软件详情')
            ->icon('heroicon-m-squares-plus')
            ->url(function (Software $software) {
                return SoftwareResource::getUrl('view', ['record' => $software->getKey()]);
            });
    }
}
