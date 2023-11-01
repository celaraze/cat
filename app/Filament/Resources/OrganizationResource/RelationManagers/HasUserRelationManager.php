<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use App\Filament\Actions\OrganizationAction;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HasUserRelationManager extends RelationManager
{
    protected static string $relationship = 'hasUsers';

    protected static ?string $title = '成员';

    protected static ?string $icon = 'heroicon-m-users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('名称'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                OrganizationAction::createHasUser($this->getOwnerRecord()),
            ])
            ->actions([
                OrganizationAction::deleteHasUser(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
