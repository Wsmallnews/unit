<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class SortUsers extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CustomerResource::class;

    protected string $view = 'filament.resources.customers.pages.sort-users';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
