<?php

namespace App\Filament\Resources\TestImageResource\Pages;

use App\Filament\Resources\TestImageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestImage extends CreateRecord
{
    protected static string $resource = TestImageResource::class;
}
