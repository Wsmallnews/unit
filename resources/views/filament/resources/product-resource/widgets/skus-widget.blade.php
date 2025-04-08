<x-filament-widgets::widget>
    <x-filament-tables::container>

        <div class="flex justify-end p-4">
            {{ $this->operateSkuAction }}
        </div>


        @if(count($skus))
            <x-filament-tables::table>
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <x-filament-tables::header-cell>
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                规格名
                            </span>
                        </x-filament-tables::header-cell>
                        <x-filament-tables::header-cell>
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                规格值
                            </span>
                        </x-filament-tables::header-cell>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @foreach($skus as $sku)
                        <x-filament-tables::row>
                            <x-filament-tables::cell>
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                    <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  ">
                                        {{ $sku['name'] }}
                                    </span>
                                </div>
                            </x-filament-tables::cell>
                            <x-filament-tables::cell>
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                    <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  ">
                                        {{ collect($sku['children'])
                                            ->join(', ') }}
                                    </span>
                                </div>
                            </x-filament-tables::cell>
                        </x-filament-tables::row>
                    @endforeach
                </tbody>
            </x-filament-tables::table>
        @else
            <x-filament-tables::empty-state heading="No Product Sku Configured" icon="lucide-shapes"></x-filament-tables::empty-state>
        @endif



        {{-- <form wire:submit="create">
            @foreach (\Wsmallnews\Product\Enums\ProductSkuType::cases() as $value => $label)
                <div class="flex ">
                    <label class="flex gap-x-3">
                        <x-filament::input.radio
                            name="sku_type"
                            :value="$value"
                            :checked="$this->data['sku_type'] === $value"
                            wire:model="data.sku_type"
                        />

                        <div class="text-sm leading-6">
                            <span class="font-medium text-gray-950 dark:text-white">
                                {{ $label }}
                            </span>

                            <p class="text-gray-500 dark:text-gray-400">
                                这是描述内容
                            </p>
                        </div>
                    </label>
                </div>
            @endforeach


            {{ $this->operateSkuAction }}


            {{ $this->form }}


        </form> --}}
        <x-filament-actions::modals />
    </x-filament-tables::container>
</x-filament-widgets::widget>


