<div class="flex flex-row">
    <!-- Filtros -->
    <div class="w-1/5">
        <div class="border border-light rounded px-4 py-2 mt-8 bg-gray-50 dark:bg-gray-800">
            <div class="mb-4">
                <x-label for="product.search" value="Producto" />
                <x-input class="block w-full" id="product.search" type="text" wire:model.live.debounce.600ms="search" wire:loading.attr='disabled'
                    placeholder="Buscar productos..." />
            </div>

            <div class="mb-4">
                <x-label for="product.category" value="Categoria" />
                <x-select id="product.category" wire:model.live="category" class="block w-full"  wire:loading.attr='disabled'>
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="mb-4">
                <x-label for="product.min" value="Precio mínimo (Bs)"  />

                <div x-data="{ currentVal: 0 }" class="flex w-full items-center gap-4 text-slate-700 dark:text-slate-300">
                    <input wire:model.live.debounce.600ms="minPrice" x-model="currentVal" id="rangeSlider"
                        type="range" wire:loading.attr='disabled'
                        class="h-2 w-full appearance-none bg-slate-300 focus:outline-blue-700 dark:bg-slate-600 dark:focus:outline-blue-600 disabled:bg-slate-200 dark:disabled:bg-slate-700 [&::-moz-range-thumb]:size-4 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:border-none [&::-moz-range-thumb]:bg-blue-700 active:[&::-moz-range-thumb]:scale-110 [&::-moz-range-thumb]:dark:bg-blue-600 [&::-webkit-slider-thumb]:size-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:border-none [&::-webkit-slider-thumb]:bg-blue-700 active:[&::-webkit-slider-thumb]:scale-110 [&::-webkit-slider-thumb]:dark:bg-blue-600 [&::-moz-range-thumb]:rounded-full [&::-webkit-slider-thumb]:rounded-full rounded-full"
                        min="0" max="500" step="50" />
                    <span class="w-10 text-md font-bold text-black dark:text-white" x-text="currentVal"></span>
                </div>
            </div>

            <div class="mb-4">
                <x-label for="product.max" value="Precio máximo (Bs)" />
                <div x-data="{ currentVal: 1000 }" class="flex w-full items-center gap-4 text-slate-700 dark:text-slate-300">
                    <input x-model="currentVal" wire:model.live.debounce.600ms="maxPrice" id="rangeSlider" type="range"
                        class="h-2 w-full appearance-none bg-slate-300 focus:outline-blue-700 dark:bg-slate-600 dark:focus:outline-blue-600 disabled:bg-slate-200 dark:disabled:bg-slate-700  [&::-moz-range-thumb]:size-4 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:border-none [&::-moz-range-thumb]:bg-blue-700 active:[&::-moz-range-thumb]:scale-110 [&::-moz-range-thumb]:dark:bg-blue-600 [&::-webkit-slider-thumb]:size-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:border-none [&::-webkit-slider-thumb]:bg-blue-700 active:[&::-webkit-slider-thumb]:scale-110 [&::-webkit-slider-thumb]:dark:bg-blue-600 [&::-moz-range-thumb]:rounded-full [&::-webkit-slider-thumb]:rounded-full rounded-full"
                        min="0" max="1000" step="50" wire:loading.attr='disabled'/>
                    <span class="w-10 text-md font-bold text-black dark:text-white" x-text="currentVal"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos -->
    <div class="w-4/5 p-4">
        <div class="flex flex-row flex-wrap justify-start">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <!-- Paginación centrada -->
        <div class="mt-4 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
