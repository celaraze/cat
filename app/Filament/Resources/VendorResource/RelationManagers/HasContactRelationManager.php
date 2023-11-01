<?php

namespace App\Filament\Resources\VendorResource\RelationManagers;

use App\Filament\Actions\VendorAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HasContactRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    protected static ?string $title = '联系人';

    protected static ?string $icon = 'heroicon-o-user';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('名称'),
                Forms\Components\TextInput::make('phone_number')
                    ->maxLength(255)
                    ->required()
                    ->label('电话'),
                Forms\Components\TextInput::make('email')
                    ->maxLength(255)
                    ->email()
                    ->label('邮箱'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('名称'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->label('电话'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('邮箱'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // 添加联系人
                VendorAction::createVendorHasContact($this->getOwnerRecord()->getKey()),
            ])
            ->
            actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ]);
    }
}
