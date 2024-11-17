<div>
    <div class="mb-4 flex items-center justify-between">
        <div>
            <x-input wire:model.live="search" type="text" placeholder="Buscar movimientos..." class="w-full" />
        </div>
        <div class="flex space-x-4">
            <div>
                <x-select wire:model.live="selectedProduct" class="w-full">
                    <option value="">Todos los productos</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <x-select wire:model.live="selectedType" class="w-full">
                    <option value="">Todos los tipos</option>
                    <option value="IN">Entrada</option>
                    <option value="OUT">Salida</option>
                </x-select>
            </div>
        </div>
        <x-spinner wire:loading size="5" />
        @can('inventory.create')
            <div>
                <x-button wire:click="create">
                    <x-spinner wire:loading wire:target="create" size="4" class="mx-20" />
                    <span wire:loading.remove wire:target='create'>Registrar Movimiento</span>
                </x-button>
            </div>
        @endcan
    </div>

    <table class="w-full table-auto">
        <thead class="border border-light">
            <tr>
                <th class="px-4 py-2">
                    <button wire:click="sortBy('id')">
                        ID
                        @if ($sortField === 'id')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="px-4 py-2">
                    <button wire:click="sortBy('created_at')">
                        Fecha
                        @if ($sortField === 'created_at')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="px-4 py-2">Producto</th>
                <th class="px-4 py-2">Tipo</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movements as $movement)
                <tr>
                    <td class="border border-light px-4 py-2">{{ $movement->id }}</td>
                    <td class="border border-light px-4 py-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border border-light px-4 py-2">{{ $movement->product->name }}</td>
                    <td class="border border-light px-4 py-2">
                        <span
                            class="px-2 py-1 rounded {{ $movement->type === 'IN' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $movement->type === 'IN' ? 'Entrada' : 'Salida' }}
                        </span>
                    </td>
                    <td class="border border-light px-4 py-2">{{ $movement->quantity }}</td>
                    <td class="border border-light px-4 py-2">{{ $movement->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $movements->links() }}
    </div>

    <x-modal name="movement-form" :show="false">
        <div class="p-6">
            <form id="movementForm" onsubmit="event.preventDefault(); confirmMovement();">
                <h2 class="text-lg font-medium">Registrar Movimiento de Inventario</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="product_id" value="Producto" />
                        <x-select id="product_id" wire:model="product_id" class="mt-1 block w-full" required>
                            <option value="">Seleccione un producto</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (Stock:
                                    {{ $product->stock }})</option>
                            @endforeach
                        </x-select>
                        @error('product_id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <x-label for="type" value="Tipo de Movimiento" />
                        <x-select id="type" wire:model="type" class="mt-1 block w-full" required>
                            <option value="">Seleccione el tipo</option>
                            <option value="IN">Entrada</option>
                            <option value="OUT">Salida</option>
                        </x-select>
                        @error('type')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <x-label for="quantity" value="Cantidad" />
                        <x-input id="quantity" type="number" wire:model="quantity" class="mt-1 block w-full" required
                            min="1" />
                        @error('quantity')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4 col-span-2">
                        <x-label for="notes" value="Notas" />
                        <x-textarea id="notes" wire:model="notes" class="mt-1 block w-full" />
                        @error('notes')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" class="mr-2" x-on:click="$dispatch('close')">
                        Cerrar
                    </x-secondary-button>

                    <x-button type="submit" wire:loading.attr='disabled'>
                        <x-spinner wire:loading wire:target="save" size="4" class="mx-6" />
                        <span wire:loading.remove wire:target='save'>Guardar</span>
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    @push('scripts')
        <script>
            function confirmMovement() {
                const form = document.getElementById('movementForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
     
                Livewire.dispatch('save');
            }
        </script>
    @endpush
</div>
