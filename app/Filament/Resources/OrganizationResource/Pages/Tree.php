<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use App\Models\Organization;
use Filament\Actions\CreateAction;
use SolutionForest\FilamentTree\Actions\Action;
use SolutionForest\FilamentTree\Actions\DeleteAction;
use SolutionForest\FilamentTree\Actions\EditAction;
use SolutionForest\FilamentTree\Resources\Pages\TreePage as BasePage;

class Tree extends BasePage
{
    protected static string $resource = OrganizationResource::class;

    protected static int $maxDepth = 5;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-m-plus')
                ->label('新增')
                ->createAnother(false),
        ];
    }

    protected function getTreeActions(): array
    {
        return [
            Action::make('成员')
                ->icon('heroicon-m-users')
                ->link()
                ->action(function (Organization $organization) {
                    $this->redirect('organizations/'.$organization->getKey());
                })
                ->visible(function () {
                    return auth()->user()->can('view_organization');
                }),
            EditAction::make()
                ->link()
                ->visible(function () {
                    return auth()->user()->can('update_organization');
                }),
            DeleteAction::make()
                ->link()
                ->visible(function () {
                    return auth()->user()->can('delete_organization');
                }),
        ];
    }
}
