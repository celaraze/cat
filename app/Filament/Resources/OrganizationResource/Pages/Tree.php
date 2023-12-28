<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use App\Models\Organization;
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Actions\Action;
use SolutionForest\FilamentTree\Actions\DeleteAction;
use SolutionForest\FilamentTree\Actions\EditAction;
use SolutionForest\FilamentTree\Resources\Pages\TreePage as BasePage;

class Tree extends BasePage
{
    protected static string $resource = OrganizationResource::class;

    protected static int $maxDepth = 5;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    /**
     * 重写方法为了让 Tree 可以正确显示 URL
     * 在 sub_navigation 模式下
     */
    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
        return '/organizations';
    }

    public static function getNavigationLabel(): string
    {
        return __('cat/action.back');
    }

    protected function getHeaderActions(): array
    {
        return [
            // 创建
            CreateAction::make()
                ->slideOver()
                ->icon('heroicon-m-plus')
                ->label(__('cat/organization.action.create'))
                ->createAnother(false)
                ->closeModalByClickingAway(false)
                ->visible(function () {
                    return auth()->user()->can('create_organization');
                }),
        ];
    }

    protected function getTreeActions(): array
    {
        return [
            // 查看成员
            Action::make(__('cat/organization.action.view'))
                ->icon('heroicon-m-eye')
                ->color('info')
                ->link()
                ->action(function (Organization $organization) {
                    $this->redirect('organizations/'.$organization->getKey());
                })
                ->visible(function () {
                    return auth()->user()->can('view_organization');
                }),
            // 编辑
            EditAction::make()
                ->link()
                ->slideOver()
                ->closeModalByClickingAway(false)
                ->visible(function () {
                    return auth()->user()->can('update_organization');
                }),
            // 删除
            DeleteAction::make()
                ->link()
                ->closeModalByClickingAway(false)
                ->visible(function () {
                    return auth()->user()->can('delete_organization');
                }),
        ];
    }
}
