<?php

namespace App\Filament\Resources\TestImageResource\Pages;

use App\Filament\Resources\TestImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTestImage extends ViewRecord
{
    protected static string $resource = TestImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
