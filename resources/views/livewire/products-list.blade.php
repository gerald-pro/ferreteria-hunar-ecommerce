<div>
    <div class="mb-4 flex items-center justify-between">
        <div>
            <x-input wire:model.live="search" type="text" placeholder="Buscar productos..." class="w-full" />
        </div>
        <div>
            <x-select wire:model.live="selectedCategory" class="w-full">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-select>
        </div>
        <x-spinner wire:loading size="5" />
        @can('product.create')
            <div>
                <x-button wire:click="create">
                    <x-spinner wire:loading wire:target="create" size="4" class="mx-4" />
                    <span wire:loading.remove wire:target='create'>Añadir</span>
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
                <th class="px-4 py-2">Imagen</th>
                <th class="px-4 py-2">
                    <button wire:click="sortBy('name')">
                        Nombre
                        @if ($sortField === 'name')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="px-4 py-2">
                    <button wire:click="sortBy('price')">
                        Precio
                        @if ($sortField === 'price')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="px-4 py-2">
                    <button wire:click="sortBy('stock')">
                        Stock
                        @if ($sortField === 'stock')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="px-4 py-2">Categoría</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td class="border border-light px-4 py-2">{{ $product->id }}</td>
                    <td class="border border-light px-4 py-2">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-16 h-16 object-cover">
                        @endif
                    </td>
                    <td class="border border-light px-4 py-2">{{ $product->name }}</td>
                    <td class="border border-light px-4 py-2">{{ number_format($product->price, 2) }} Bs</td>
                    <td class="border border-light px-4 py-2">{{ $product->stock }} </td>
                    <td class="border border-light px-4 py-2">{{ $product->category->name }}</td>
                    <td class="border border-light px-4 py-2">
                        @can('product.edit')
                            <x-button wire:click="edit({{ $product->id }})" class="mr-2">
                                <x-spinner wire:loading wire:target="edit({{ $product->id }})" size="3" />
                                <span wire:loading.remove wire:target='edit({{ $product->id }})'
                                    class="fas fa-edit"></span>
                            </x-button>
                        @endcan
                        @can('product.delete')
                            <x-button onclick="confirmDelete({{ $product->id }})" class="bg-red-500 hover:bg-red-700">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <x-modal name="product-form" :show="false">
        <div class="p-6">
            <form id="productForm" onsubmit="event.preventDefault(); confirmOrder();">
                <h2 class="text-lg font-medium">{{ $productId ? 'Editar' : 'Crear' }} Producto</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4 col-span-2">
                        <x-label for="name" value="Nombre" />
                        <x-input id="name" type="text" wire:model="name" class="mt-1 block w-full" required />
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4 col-span-2">
                        <x-label for="description" value="Descripción" />
                        <x-textarea id="description" wire:model="description" class="mt-1 block w-full" required />
                        @error('description')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <x-label for="price" value="Precio" />
                        <x-input id="price" type="number" step="0.01" wire:model="price" min="0.01"
                            class="mt-1 block w-full" required />
                        @error('price')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <x-label for="category_id" value="Categoría" />
                        <x-select id="category_id" wire:model="category_id" class="mt-1 block w-full" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-select>
                        @error('category_id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4 col-span-2">
                        <x-label for="image" value="Imagen" />
                        <x-input id="image" type="file" wire:model="image" class="mt-1 block w-full" />
                        @error('image')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <x-spinner wire:loading wire:target="image" size="6" />

                    @if ($existingImageUrl)
                        <div class="mt-4 col-span-2">
                            <img src="{{ $existingImageUrl }}" alt="Imagen de previsualización"
                                class="w-32 h-32 object-cover">
                            <x-button wire:click="deleteImage" class="bg-red-500 hover:bg-red-700 mt-2">
                                Eliminar Imagen
                            </x-button>
                        </div>
                    @endif
                    @if ($image)
                        <div class="mt-4 col-span-2">
                            <img src="{{ $image->temporaryUrl() }}" alt="Imagen de previsualización"
                                class="w-32 h-32 object-cover">
                        </div>
                    @endif
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
            function confirmOrder() {
                const form = document.getElementById('productForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                Livewire.dispatch('save');
            }
        </script>

        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Esta acción es irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete-product', {
                            productId: id
                        });
                    }
                })
            }
        </script>
    @endpush
</div>
