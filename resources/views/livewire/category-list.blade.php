<div>
    <div class="mb-4 flex items-center justify-between">
        <div>
            <x-input wire:model.live="search" type="text" placeholder="Buscar categorías..." class="w-full" />
        </div>
        <x-spinner wire:loading wire:target='search' size="5" />
        @can('category.create')
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
                <th class="px-4 py-2">Descripción</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td class="border border-light px-4 py-2">{{ $category->id }}</td>
                    <td class="border border-light px-4 py-2">{{ $category->name }}</td>
                    <td class="border border-light px-4 py-2">{{ $category->description }}</td>
                    <td class="border border-light px-4 py-2">
                        @can('category.edit')
                            <x-button wire:click="edit({{ $category->id }})" class="mr-2">
                                <x-spinner wire:loading wire:target="edit({{ $category->id }})" size="3" />
                                <span wire:loading.remove wire:target='edit({{ $category->id }})'
                                    class="fas fa-edit"></span>
                            </x-button>
                        @endcan
                        @can('category.delete')
                            <x-button onclick="confirmDelete({{ $category->id }})" class="bg-red-500 hover:bg-red-700">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <x-modal name="category-form" :show="false" maxWidth="md">
        <div class="p-6">
            <form id="categoryForm" onsubmit="event.preventDefault(); confirmSave();">
                <h2 class="text-lg font-medium">{{ $categoryId ? 'Editar' : 'Crear' }} Categoría</h2>
                <div class="mt-4">
                    <x-label for="name" value="Nombre" />
                    <x-input id="name" type="text" wire:model="name" class="mt-1 block w-full" required />
                    @error('name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-4">
                    <x-label for="description" value="Descripción" />
                    <x-textarea id="description" wire:model="description" class="mt-1 block w-full" />
                    @error('description')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
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
            function confirmSave() {
                const form = document.getElementById('categoryForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                Livewire.dispatch('save');
            }

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
                        Livewire.dispatch('delete-category', {
                            categoryId: id
                        });
                    }
                })
            }
        </script>
    @endpush
</div>
