<div>
    <!-- Filtros -->
    <div class="mb-4 flex flex-wrap gap-4">
        <div class="flex space-x-2">
            <x-input wire:model.live.debounce.400ms="search" placeholder="Buscar pagos..." class="w-64" />

            <x-select wire:model.live="filterStatus" class="w-40">
                <option value="">Estado: Todos</option>
                <option value="PENDIENTE">Pendiente</option>
                <option value="PAGADO">Pagado</option>
                <option value="CANCELADO">Cancelado</option>
            </x-select>
        </div>

        <div class="flex space-x-2">
            <x-input type="date" wire:model.live="startDate" class="w-40" />
            <x-input type="date" wire:model.live="endDate" class="w-40" />
        </div>

        <x-spinner wire:loading size="4" class="mx-6" />
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
                    <button wire:click="sortBy('paid_amount')">
                        Monto (Bs)
                        @if ($sortField === 'paid_amount')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="border border-gray-300 px-4 py-2">
                    <button wire:click="sortBy('status')">
                        Estado
                        @if ($sortField === 'status')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="border border-gray-300 px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $payment->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $payment->user_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($payment->paid_amount, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($payment->status == 'PAGADO')
                            <span class="text-green-600 font-semibold">{{ $payment->status }}</span>
                        @elseif ($payment->status == 'CANCELADO')
                            <span class="text-red-600 font-semibold">{{ $payment->status }}</span>
                        @else
                            <span class="text-yellow-600 font-semibold">{{ $payment->status }}</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <x-button-secondary
                            wire:click="dispatch('show-payment-details', { paymentId: {{ $payment->id }} })"
                            wire:loading.attr='disabled'>
                            <i class="fas fa-eye fa-fw"></i>
                        </x-button-secondary>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
