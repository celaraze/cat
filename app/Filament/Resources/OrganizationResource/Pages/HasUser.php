<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Actions\OrganizationAction;
use App\Filament\Resources\OrganizationResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class HasUser extends ManageRelatedRecords
{
    protected static string $resource = OrganizationResource::class;

    protected static string $relationship = 'hasUsers';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = '成员';

    public static function getNavigationLabel(): string
    {
        return '成员';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label('名称'),
            ])
            ->filters([

            ])
            ->headerActions([
                // 新增成员
                OrganizationAction::createHasUser($this->getOwnerRecord()),
            ])
            ->actions([
                // 删除成员
                OrganizationAction::deleteHasUser(),
            ])
            ->bulkActions([

            ]);
    }
}
