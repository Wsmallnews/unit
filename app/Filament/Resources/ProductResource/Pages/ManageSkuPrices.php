<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageSkuPrices extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'skuPrices';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Sku Prices';
    }



    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }



    protected function getHeaderWidgets(): array
    {
        return [
            ProductResource\Widgets\SkusWidget::class,
        ];
    }



    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('product_sku_text')
    //                 ->required()
    //                 ->maxLength(255),
    //         ]);
    // }

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->recordTitleAttribute('product_sku_text')
    //         ->columns([
    //             Tables\Columns\TextColumn::make('product_sku_text'),
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->headerActions([
    //             Tables\Actions\CreateAction::make(),
    //             Tables\Actions\AssociateAction::make(),
    //         ])
    //         ->actions([
    //             Tables\Actions\ViewAction::make(),
    //             Tables\Actions\EditAction::make(),
    //             Tables\Actions\DissociateAction::make(),
    //             Tables\Actions\DeleteAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DissociateBulkAction::make(),
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }
}
