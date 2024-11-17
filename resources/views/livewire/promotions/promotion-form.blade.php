<div>
    <div x-data="{
        selectedProducts: @entangle('selectedProducts'),
    
        init() {
    
            let tomSelect = new TomSelect(this.$refs.products, {
                plugins: {
                    remove_button: {
                        title: 'Eliminar producto',
                    }
                },
                onChange: (values) => {
                    this.selectedProducts = values;
                }
            });
    
            this.$watch('selectedProducts', value => {
                if (JSON.stringify(tomSelect.getValue()) !== JSON.stringify(value)) {
                    tomSelect.setValue(value, true);
                }
            });
    
            $wire.on('promotionUpdated', () => {
                tomSelect.setValue(this.selectedProducts, true);
            });
            tomSelect.setValue(this.selectedProducts, true);
            console.log('TomSelect initialized with:', this.selectedProducts);
        },
        submit() {
            console.log(this.selectedProducts);
            $wire.set('selectedProducts', this.selectedProducts, true);
        }
    }">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-2">
                    <x-label for="name" value="Nombre" />
                    <x-input id="name" type="text" wire:model="name" required class="block w-full"/>
                    @error('name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-1">
                    <x-label for="discount_percentage" value="Descuento (%)" />
                    <x-input class="block w-full" id="discount_percentage" type="number" step="0.01" min="0" max="100"
                        wire:model="discount_percentage" required />
                    @error('discount_percentage')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-1">
                    <div class="flex items-center ps-4 border border-light rounded mt-6">
                        <input wire:model="is_active" id="bordered-checkbox-1" type="checkbox" name="bordered-checkbox" class="w-4 h-4 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 ">
                        <x-label for="bordered-checkbox-1" class="py-2 ms-2" value="Activo" />
                    </div>
                    @error('is_active')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                </div>

                <div class="col-span-2">
                    <x-label for="start_date" value="Fecha de inicio" />
                    <x-input id="start_date" type="datetime-local" wire:model="start_date" required class="block w-full"/>
                    @error('start_date')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-2">
                    <x-label for="end_date" value="Fecha de fin" />
                    <x-input id="end_date" type="datetime-local" wire:model="end_date" class="block w-full" required />
                    @error('end_date')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-2">
                    <x-label for="description" value="Descripción" />
                    <x-textarea id="description" wire:model="description" />
                    @error('description')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-2" wire:ignore>
                    <x-label for="products" value="Productos" />
                    <select class="w-full block" id="products" x-ref="products" multiple>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                data-category="{{ $product->category->name ?? 'Sin categoría' }}">
                                {{ $product->name }} - ${{ number_format($product->price, 2) }} -
                                {{ $product->category->name ?? 'Sin categoría' }}
                            </option>
                        @endforeach
                    </select>
                    @error('selectedProducts')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-button type="submit" wire:loading.attr="disabled" x-on:click="submit">
                    Guardar
                </x-button>
            </div>
        </form>
    </div>
</div>
