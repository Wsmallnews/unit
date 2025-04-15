<?php

namespace App\Filament\Resources\TestImageResource\Pages;

use App\Filament\Resources\TestImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestImage extends EditRecord
{
    protected static string $resource = TestImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
