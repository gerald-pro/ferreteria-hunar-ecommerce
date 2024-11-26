<div>
    <!-- Filtros -->
    <div class="mb-4 flex space-x-4">
        <div class="flex space-x-2">
            <x-input wire:model.live.debounce.400ms="search" placeholder="Buscar pedidos..." class="w-64" />
            <x-select wire:model.live="filterStatus" class="w-40">
                <option value="">Estado: Todos</option>
                <option value="PENDIENTE">Pendiente</option>
                <option value="COMPLETADO">Completado</option>
                <option value="CANCELADO">Cancelado</option>
            </x-select>

            <x-spinner wire:loading size="4" class="mx-6" />
        </div>
    </div>

    <!-- Tabla -->
    <table class="w-full table-auto">
        <thead class="border border-light">
            <tr>
                <th class="border border-gray-300 px-4 py-2">
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
                <th class="border border-gray-300 px-4 py-2">
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
                <th class="border border-gray-300 px-4 py-2">Usuario</th>
                <th class="border border-gray-300 px-4 py-2">
                    <button wire:click="sortBy('total_amount')">
                        Total
                        @if ($sortField === 'total_amount')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="border border-gray-300 px-4 py-2">
                    Monto Pagado
                </th>
                <th class="border border-gray-300 px-4 py-2">Entrega</th>
                <th class="border border-gray-300 px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $order->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $order->user->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($order->total_amount, 2) }} Bs</td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ number_format($order->payments()->where('status', 'PAGADO')->sum('paid_amount'), 2) }} Bs
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($order->delivery_status == 'COMPLETADO')
                            <span class="text-green-600 font-semibold">{{ $order->delivery_status }}</span>
                        @else
                            <span class="text-yellow-600 font-semibold">{{ $order->delivery_status }}</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2 space-x-2">
                        <x-button-secondary
                            wire:click="dispatch('show-order-details', { orderId: {{ $order->id }} })"
                            wire:loading.attr='disabled'>
                            <i class="fas fa-eye fa-fw"></i>
                        </x-button-secondary>

                        <x-button-secondary
                            wire:click="dispatch('show-order-payments', { orderId: {{ $order->id }} })"
                            wire:loading.attr='disabled'>
                            <i class="fas fa-file-invoice   fa-fw"></i>
                        </x-button-secondary>

                        @if ($order->delivery_status === 'PENDIENTE')
                            <x-button onclick="confirmComplete({{ $order->id }})"
                                class="bg-emerald-600 hover:bg-emerald-700">
                                <i class="fas fa-check fa-fw"></i>
                            </x-button>
                        @endif

                        @if ($order->delivery_status == 'PENDIENTE')
                            <x-button onclick="confirmCancel({{ $order->id }})" class="bg-red-600 hover:bg-red-700">
                                <i class="fas fa-x fa-fw"></i>
                            </x-button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
