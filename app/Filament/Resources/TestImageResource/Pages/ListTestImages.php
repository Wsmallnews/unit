<?php

namespace App\Filament\Resources\TestImageResource\Pages;

use App\Filament\Resources\TestImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestImages extends ListRecords
{
    protected static string $resource = TestImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
