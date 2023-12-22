<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Filament\Actions\VendorAction;
use App\Filament\Forms\VendorHasContactForm;
use App\Filament\Resources\VendorResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class Contact extends ManageRelatedRecords
{
    protected static string $resource = VendorResource::class;

    protected static string $relationship = 'contacts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat.menu.vendor_has_contact');
    }

    public function getBreadcrumb(): string
    {
        return __('cat.menu.vendor_has_contact');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(VendorHasContactForm::createOrEdit());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.vendor_has_contact.name')),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.vendor_has_contact.phone_number')),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.vendor_has_contact.email')),
            ])
            ->filters([

            ])
            ->headerActions([
                // 添加联系人
                VendorAction::createHasContact($this->getOwnerRecord()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

            ]);
    }
}
