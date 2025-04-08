<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
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

    public ?array $skuPrices = [];

    public ?array $skus = [];

    protected static string $view = 'filament.resources.product-resource.widgets.skus-widget';


    public function mount(): void
    {
        $this->skus = $this->record->skus->toArray();

        $this->skuPrices = ['sku_prices' => $this->record->skuPrices->toArray()];
    }



    public function operateSkuAction()
    {
        return Action::make('operateSku')
            ->fillForm(function () {
                return ['skus' => $this->skus];
            })
            ->form([
                Components\Repeater::make('skus')->hiddenLabel()
                    ->schema([
                        Components\TextInput::make('name')->hiddenLabel()->required()->columnSpan(1),
                        Components\Fieldset::make('children')->schema([
                            Components\Repeater::make('children')->hiddenLabel()->simple(
                                Components\TextInput::make('name')->hiddenLabel()->required()->columnSpanFull(),
                            )->grid(3),
                        ])->columns(1)->columnSpanFull()
                    ])->columns(3)
            ])
            ->action(function ($data) {
                $this->skus = $data['skus'];

                $this->setSkuPrices();

            });
    }



    public function setSkuPrices()
    {
        
    }



    public function fillForm()
    {
        $this->form->fill($this->record ? $this->record->toArray() : []);
    }


    public function form(Form $form): Form
    {
        return $form->schema([
            TableRepeater::make('sku_prices')
                ->headers([
                    Header::make('name')->width('150px'),
                ])
                ->schema([
                    Components\TextInput::make('name')->required()->columnSpan(1),
                ])
                ->streamlined()
                ->emptyLabel('please set skus')
                ->columnSpan('full')
        ])
        ->statePath('skuPrices');
    }


    public function saveAction()
    {
        return Action::make('save')
            ->action(function () {
                dd($this->form->getState());
            });
    }

}
