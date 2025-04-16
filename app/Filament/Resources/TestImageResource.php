<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestImageResource\Pages;
use App\Filament\Resources\TestImageResource\RelationManagers;
use App\Models\TestImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestImageResource extends Resource
{
    protected static ?string $model = TestImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('标题'),
                \Wsmallnews\Support\Filament\Forms\Fields\MediableFileUpload::make('image')->label('主图')
                    ->directory('test-images/' . date('Ymd'))
                    ->image()
                    ->tag('main')
                    ->required()
                    ->openable()
                    ->downloadable()
                    ->uploadingMessage('产品主图上传中...')
                    ->imagePreviewHeight('100'),

                \Wsmallnews\Support\Filament\Forms\Fields\MediableFileUpload::make('carousel')->label('轮播图')
                    ->directory('test-images/' . date('Ymd'))
                    ->multiple()
                    ->image()
                    ->tag('carousel')
                    ->required()
                    ->openable()
                    ->downloadable()
                    ->reorderable()
                    ->appendFiles()
                    ->minFiles(1)
                    ->maxFiles(20)
                    ->uploadingMessage('轮播图片上传中...')
                    ->imagePreviewHeight('100')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestImages::route('/'),
            'create' => Pages\CreateTestImage::route('/create'),
            'view' => Pages\ViewTestImage::route('/{record}'),
            'edit' => Pages\EditTestImage::route('/{record}/edit'),
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
