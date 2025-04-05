<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Filament\Widgets\Widget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Illuminate\Database\Eloquent\Model;

class SkusWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?Model $record = null;

    public ?array $data = [];

    public ?array $skus = [];

    protected static string $view = 'filament.resources.product-resource.widgets.skus-widget';


    public function mount(): void
    {
        $this->form->fill($this->record ? $this->record->toArray() : []);
    }



    public function operateSkuAction()
    {
        return Action::make('operateSku')
            ->form([
                Components\Repeater::make('skus')
                    ->schema([
                        Components\TextInput::make('name')->required()->columnSpan(1),
                        Components\Fieldset::make('children')->schema([
                            Components\Repeater::make('children')->hiddenLabel()->simple(
                                Components\TextInput::make('name')->required()->columnSpanFull(),
                            )->grid(3),
                        ])->columns(1)->columnSpanFull()
                    ])->columns(3)
            ])
            ->action(function ($data) {
                $this->skus = $data['skus'];
            });
    }



    public function form(Form $form): Form
    {
        return $form->schema([
            // Components\Radio::make('sku_type')
            //     ->label('规格')
            //     ->options(Enums\ProductSkuType::class)
            //     ->default(Enums\ProductSkuType::Single->value)
            //     // ->afterStateUpdated(fn(Set $set, ?string $state) => $set('skuPrice.sku_type', $state))
            //     ->live()
            //     ->inline(),
        ])
        ->statePath('data')
        ->model($this->record);
    }


    public function saveAction()
    {
        return Action::make('save')
            ->action(function () {
                dd($this->form->getState());
            });
    }

}
