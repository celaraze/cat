<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlowHasFormResource\Pages;
use App\Models\FlowHasForm;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class FlowHasFormResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = FlowHasForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

            ])
            ->filters([

            ])
            ->actions([

            ])
            ->bulkActions([

            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'process_flow_has_form',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
        ];
    }
}
