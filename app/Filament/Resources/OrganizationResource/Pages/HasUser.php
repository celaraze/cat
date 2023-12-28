<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Actions\OrganizationHasUserAction;
use App\Filament\Resources\OrganizationResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class HasUser extends ManageRelatedRecords
{
    protected static string $resource = OrganizationResource::class;

    protected static string $relationship = 'hasUsers';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.organization_has_user');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/organization_has_user.user')),
            ])
            ->filters([

            ])
            ->headerActions([
                // 新增成员
                OrganizationHasUserAction::create($this->getOwnerRecord())
                    ->visible(function () {
                        return auth()->user()->can('create_has_user_organization');
                    }),
            ])
            ->actions([
                // 删除成员
                OrganizationHasUserAction::delete()
                    ->visible(function () {
                        return auth()->user()->can('delete_has_user_organization');
                    }),
            ])
            ->bulkActions([

            ]);
    }
}
