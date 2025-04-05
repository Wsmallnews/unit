<x-filament-widgets::widget>
    <x-filament::section>
        {{ $this->operateSkuAction }}


        @foreach ($skus as $sku)
            {{$sku['name']}}: @foreach ($sku['children'] as $child) {{$child}}@endforeach
        @endforeach

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
    </x-filament::section>
    
</x-filament-widgets::widget>


