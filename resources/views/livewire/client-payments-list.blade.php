<div>
    <div class="mb-4 flex space-x-4 justify-between">
        <div>
            <x-input type="text" wire:model.live.debounce.400ms="search" placeholder="Buscar pagos..." class="w-40" />

            <x-select wire:model.live="status">
                <option value="">Todos los estados</option>
                <option value="PAGADO">Pagado</option>
                <option value="PENDIENTE">Pendiente</option>
                <option value="CANCELADO">Cancelado</option>
            </x-select>

            <x-spinner wire:loading size="4" class="mx-6" />
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
                <th class="px-4 py-2">Pedido</th>
                <th class="px-4 py-2">Monto Total</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td class="border border-light px-4 py-2">{{ $payment->id }}</td>
                    <td class="border border-light px-4 py-2">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border border-light px-4 py-2">{{ $payment->order->id }}</td>
                    <td class="border border-light px-4 py-2">{{ number_format($payment->paid_amount, 2) }} Bs</td>
                    <td class="border border-light px-4 py-2">{{ $payment->status }}</td>
                    <td class="border border-light px-4 py-2"><x-button-secondary
                            wire:click="dispatch('show-payment-details', { paymentId: {{ $payment->order->id }} })"
                            wire:loading.attr='disabled'>
                            <i class="fas fa-eye fa-fw"></i>
                        </x-button-secondary>
                        @if ($payment->status === 'PENDIENTE')
                            <x-button wire:click="payOrder({{ $payment->id }})" wire:loading.attr='disabled'
                                wire:target="payOrder({{ $payment->order->id }})">

                                <x-spinner wire:loading wire:target="payOrder({{ $payment->id }})"
                                    size="3" />

                                <i wire:loading.remove wire:target="payOrder({{ $payment->id }})"
                                    class="fas fa-credit-card  fa-fw">
                                </i>
                            </x-button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>

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
