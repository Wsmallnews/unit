<?php

namespace App\Livewire;

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

    public ?array $data = [];

    protected static string $view = 'livewire.skus-widget';


    public function mount(): void
    {
        $this->form->fill();
    }


    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->action(function () {
                dd(111);
            });
    }


    public function operateSkuAction()
    {
        return Action::make('operateSku')
            ->requiresConfirmation()
            ->action(function () {
                dd(111);
            });

        // return Action::make('asdfasdf')
        //     ->requiresConfirmation()
        //     ->action(function () {
        //         dd(111);
        //     });


        return Action::make('product_sku_operate')
            // ->form([
            //     Components\Repeater::make('skus')
            //         ->relationship('skus')
            //         ->simple(
            //             Components\TextInput::make('name')->required(),
            //         )
            // ])
            // ->model($this->record)
            ->requiresConfirmation()
            ->action(function () {
                dd(111);
            });
    }

}
