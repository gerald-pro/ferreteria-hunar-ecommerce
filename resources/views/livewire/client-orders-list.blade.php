<div>
    <div class="mb-4 flex space-x-4 justify-between">
        <div>
            <x-input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar pedidos..."
                class="w-48" />

            <x-select wire:model.live="filterStatus" class="w-30">
                <option value="">Todos</option>
                <option value="PENDIENTE">Pendiente</option>
                <option value="COMPLETADO">Completado</option>
                <option value="CANCELADO">Cancelado</option>
            </x-select>

            <x-spinner wire:loading wire:target='search' size="4" class="mx-6" />
        </div>
        <x-button wire:click='updateTransactions' wire:loading.attr='disabled'>
            <x-spinner wire:loading wire:target='updateTransactions' size="5" class="mx-1" />
            <i wire:loading.remove wire:target='updateTransactions' class="fas fa-refresh fa-lg fa-fw"></i>
        </x-button>
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
                <th class="px-4 py-2">
                    Estado de Pago
                </th>
                <th class="px-4 py-2">
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
                <th class="px-4 py-2">
                    Monto Pagado
                </th>
                <th class="px-4 py-2">
                    <button wire:click="sortBy('delivery_status')">
                        Entrega
                        @if ($sortField === 'delivery_status')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="border border-light px-4 py-2">{{ $order->id }}</td>
                    <td class="border border-light px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border border-light px-4 py-2 text-center">
                        @php
                            $totalPaid = $order->payments()->where('status', 'PAGADO')->sum('paid_amount');
                        @endphp
                        @if ($totalPaid >= $order->total_amount)
                            <span class="text-green-600 font-semibold">PAGADO</span>
                        @else
                            <span class="text-yellow-600 font-semibold">PENDIENTE</span>
                        @endif
                    </td>
                    <td class="border border-light px-4 py-2">{{ number_format($order->total_amount, 2) }} Bs</td>
                    <td class="border border-light px-4 py-2">
                        {{ number_format($totalPaid, 2) }} Bs
                    </td>
                    <td class="border border-light px-4 py-2 text-center">
                        @if ($order->delivery_status == 'COMPLETADO')
                            <span class="text-green-600 font-semibold">{{ $order->delivery_status }}</span>
                        @else
                            <span class="text-yellow-600 font-semibold">{{ $order->delivery_status }}</span>
                        @endif
                    <td class="border border-light px-4 py-2">
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

                        @if ($totalPaid < $order->total_amount)
                            <x-button wire:click="showPayment({{ $order->id }})" wire:loading.attr='disabled'
                                wire:target="showPayment({{ $order->id }})">
                                <x-spinner wire:loading wire:target="showPayment({{ $order->id }})"
                                    size="3" />
                                <i wire:loading.remove wire:target="showPayment({{ $order->id }})"
                                    class="fas fa-credit-card fa-fw">
                                </i>
                            </x-button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    <!-- Modal de Pago -->
    <x-modal name="payment-modal" :show="$showPaymentModal">
        <div class="p-6">
            <h2 class="text-lg font-medium">
                Realizar Pago
            </h2>

            <div class="mt-4">
                <label for="paymentAmount" class="block text-sm font-medium text-gray-700">Monto a Pagar</label>
                <div class="mt-1">
                    <input type="number" wire:model="paymentAmount" step="0.01" max="{{ $maxPaymentAmount }}" min="0.01"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <p class="mt-2 text-sm text-gray-500">Monto máximo: {{ number_format($maxPaymentAmount, 2) }} Bs</p>
                @error('paymentAmount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>
                <x-button wire:click="processPayment" wire:loading.attr='disabled'>
                    Procesar Pago
                </x-button>
            </div>
        </div>
    </x-modal>

    <x-modal name="qr-modal" :show="$showQrModal">
        <div class="p-6">
            <h2 class="text-lg font-medium">
                Código QR de Pago
            </h2>

            <div class="mt-4 flex justify-center">
                <img src="{{ $qrImage }}" alt="QR Code" class="bg-white">
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cerrar
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>
