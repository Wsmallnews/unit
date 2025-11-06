<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'route' => 'Route',
                        'action' => 'Action',
                    ])
                    ->default('route')
                    ->required(),
                TextInput::make('name')->label('Route Name')
                    ->required()
                    ->visibleJs(<<<'JS'
                        $get('type') == 'route'
                    JS),
                TextInput::make('description')->label('Description')
                    ->required()
                    ->visibleJs(<<<'JS'
                        $get('type') == 'action'
                    JS),
            ]);
    }
}
