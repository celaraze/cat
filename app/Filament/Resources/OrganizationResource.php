<?php

namespace App\Filament\Resources;

use App\Filament\Forms\OrganizationForm;
use App\Filament\Resources\OrganizationResource\Pages\Edit;
use App\Filament\Resources\OrganizationResource\Pages\HasUser;
use App\Filament\Resources\OrganizationResource\Pages\Tree;
use App\Filament\Resources\OrganizationResource\Pages\View;
use App\Models\Organization;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-group';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('cat/menu.security');
    }

    public static function getModelLabel(): string
    {
        return __('cat/menu.organization');
    }

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.organization');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Tree::class,
            View::class,
            Edit::class,
            HasUser::class,
        ];
        $can_update_organization = auth()->user()->can('update_organization');
        if (! $can_update_organization) {
            unset($navigation_items[2]);
        }
        $can_update_organization_has_user = auth()->user()->can('update_has_user_organization');
        if (! $can_update_organization_has_user) {
            unset($navigation_items[3]);
        }

        return $page->generateNavigationItems($navigation_items);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'view_has_user',
            'create_has_user',
            'delete_has_user',
            'update_has_user',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(OrganizationForm::createOrEdit());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->schema([
                                    Group::make([
                                        TextEntry::make('name')
                                            ->label(__('cat/organization.name')),
                                    ]),
                                ]),
                        ]),
                    ]),
            ])->columnSpan(['lg' => 3]),
        ])->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index' => Tree::route('/'),
            'edit' => Edit::route('/{record}/edit'),
            'view' => View::route('/{record}'),
            'users' => HasUser::route('/{record}/has_users'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
