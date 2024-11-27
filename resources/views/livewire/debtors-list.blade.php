<div>
    <div class="mb-4 flex items-center justify-between">
        <div class="w-1/3">
            <x-input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar por nombre o CI..."
                class="w-full" />
        </div>
        <x-spinner wire:loading size="5" />
    </div>

    <table class="w-full table-auto">
        <thead class="border border-light">
            <tr>
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
                    <button wire:click="sortBy('total_debt')">
                        Total Deuda
                        @if ($sortField === 'total_debt')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th>
               {{--  <th class="px-4 py-2">
                    <button wire:click="sortBy('orders_count')">
                        Pedidos Pendientes
                        @if ($sortField === 'orders_count')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </button>
                </th> --}}
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($debtors as $debtor)
                <tr>
                    <td class="border border-light px-4 py-2">{{ $debtor->name }}</td>
                    <td class="border border-light px-4 py-2">{{ number_format($debtor->total_debt, 2) }} Bs</td>
                    {{-- <td class="border border-light px-4 py-2">{{ $debtor->orders_count }}</td> --}}
                    <td class="border border-light px-4 py-2">
                        <x-button wire:click="showDetail({{ $debtor->id }})" class="bg-blue-500 hover:bg-blue-700">
                            <x-spinner wire:loading wire:target="showDetail({{ $debtor->id }})" size="3" />
                            <span wire:loading.remove wire:target='showDetail({{ $debtor->id }})'>
                                <i class="fas fa-eye"></i>
                            </span>
                        </x-button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $debtors->links() }}
    </div>

    <x-modal name="debtor-detail" :show="false" maxWidth="3xl">

        @if ($selectedDebtor)
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-medium">Detalle de Deudas - {{ $selectedDebtor->name }}</h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium">Total Deuda:</span>
                        <span class="text-lg font-bold text-red-600">
                            {{ number_format($selectedDebtor->orders->sum('total_amount'), 2) }} Bs
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 overflow-y-scroll h-96 space-y-4">
                @foreach ($selectedDebtor->orders as $order)
                    <div class="border border-light rounded p-4 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium">Pedido #<x-link
                                        href="{{ route('admin.orders', ['order' => $order->id]) }}">{{ $order->id }}</x-link>
                                </h3>
                                <p class="text-sm ">Fecha:
                                    {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">Total Pedido:</p>
                                <p class="text-lg font-bold">{{ number_format($order->total_amount, 2) }} Bs
                                </p>
                            </div>
                        </div>
                        @if ($order->payments->count())
                            <div class="mt-4">
                                <h4 class="text-sm font-medium mb-2">Pagos Pendientes:</h4>
                                <div class="rounded border p-3">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="text-left">
                                                <th class="pb-2">Fecha</th>
                                                <th class="pb-2">Monto</th>
                                                <th class="pb-2">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->payments as $payment)
                                                <tr>
                                                    <td class="py-1">{{ $payment->created_at->format('d/m/Y') }}
                                                    </td>
                                                    <td class="py-1">
                                                        {{ number_format($payment->paid_amount, 2) }} Bs</td>
                                                    <td class="py-1">
                                                        <span
                                                            class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                                            Pendiente
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t">
                <div class="flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cerrar
                    </x-secondary-button>
                </div>
            </div>
        @endif
    </x-modal>
</div>
