<div>
    <div class="mb-4 flex items-center justify-between">
        <div>
            <x-input wire:model.live="search" type="text" placeholder="Buscar promociones..." class="w-full" />
        </div>
        <x-spinner wire:loading size="5" />
        <div>
            @can('promotion.create')
                <x-button href="{{ route('promotions.create') }}">
                    Añadir Promoción
                </x-button>
            @endcan
        </div>
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
                <th class="px-4 py-2">
                    <button wire:click="sortBy('discount_percentage')">
                        Descuento
                        @if ($sortField === 'discount_percentage')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="px-4 py-2">Fecha Inicio</th>
                <th class="px-4 py-2">Fecha Fin</th>
                <th class="px-4 py-2">Activo</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion)
                <tr>
                    <td class="border border-light px-4 py-2">{{ $promotion->id }}</td>
                    <td class="border border-light px-4 py-2">{{ $promotion->name }}</td>
                    <td class="border border-light px-4 py-2">{{ $promotion->discount_percentage }}%</td>
                    <td class="border border-light px-4 py-2">{{ $promotion->start_date->format('d/m/Y H:s') }}</td>
                    <td class="border border-light px-4 py-2">{{ $promotion->end_date->format('d/m/Y H:s') }}</td>
                    <td class="border border-light px-4 py-2">
                        <span class="{{ $promotion->is_active ? 'text-green-500' : 'text-red-500' }}">
                            {{ $promotion->is_active ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td class="border border-light px-4 py-2">
                        @can('promotion.edit')
                            <x-button href="{{ route('promotions.edit', $promotion->id) }}" class="mr-2">
                                <span class="fas fa-edit"></span>
                            </x-button>
                        @endcan
                        @can('promotion.delete')
                            <x-button onclick="confirmDelete({{ $promotion->id }})" class="bg-red-500 hover:bg-red-700">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $promotions->links() }}
    </div>

    @push('scripts')
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
                        Livewire.dispatch('delete-promotion', {
                            promotionId: id
                        });
                    }
                })
            }
        </script>
    @endpush
</div>
