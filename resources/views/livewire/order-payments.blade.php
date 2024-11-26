<div>
    <x-modal name="order-payments" :show="false" maxWidth="3xl">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Pagos del Pedido #{{ $orderId }}
            </h2>

            <div class="mt-4">
                @if (count($payments) > 0)
                    <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 dark:border-gray-600">ID</th>
                                <th class="border border-gray-300 px-4 py-2 dark:border-gray-600">Fecha</th>
                                <th class="border border-gray-300 px-4 py-2 dark:border-gray-600">Monto Pagado</th>
                                <th class="border border-gray-300 px-4 py-2 dark:border-gray-600">Estado</th>
                                <th class="border border-gray-300 px-4 py-2 dark:border-gray-600">Transacción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 dark:border-gray-600">
                                        @role('cliente')
                                            <x-link href="{{ route('customer.payments', ['payment' => $payment->id]) }}"><i
                                                    class="fas fa-link fa-fw mr-1"></i>{{ $payment->id }}</x-link>
                                        @else
                                            <x-link href="{{ route('admin.payments', ['payment' => $payment->id]) }}"><i
                                                    class="fas fa-link fa-fw mr-1"></i>{{ $payment->id }}</x-link>
                                        @endrole
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                        {{ number_format($payment->paid_amount, 2) }} Bs
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                        {{ $payment->status }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                        {{ $payment->transaction_id ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 dark:text-gray-400">
                        No hay pagos registrados para este pedido.
                    </p>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cerrar
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>
