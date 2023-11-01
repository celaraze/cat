<?php

namespace App\Filament\Resources\FlowHasFormResource\RelationManagers;

use App\Utils\FlowHasFormUtil;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormRelationManager extends RelationManager
{
    protected static string $relationship = 'forms';

    protected static ?string $title = '';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uuid')
            ->columns([
                Tables\Columns\TextColumn::make('node_name')
                    ->label('节点说明'),
                Tables\Columns\TextColumn::make('approve_comment')
                    ->label('审批意见'),
                Tables\Columns\TextColumn::make('approve_user_name')
                    ->label('审批人'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('审批时间'),
                Tables\Columns\TextColumn::make('nodeStatusText')
                    ->label('状态')
                    ->icon(function (string $state) {
                        return FlowHasFormUtil::nodeStatusTextIcons($state);
                    })
                    ->color(function (string $state) {
                        return FlowHasFormUtil::nodeStatusTextColors($state);
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }


}
