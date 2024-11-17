<div>
    <x-modal name="payment-details" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Detalles del pago
            </h2>

            <div class="mt-4">
                @if ($payment)
                    <p><strong>ID Pago: </strong> {{ $payment->id }}</p>

                    <p><strong>ID Pedido: </strong>
                        @role('cliente')
                            <x-link href="{{ route('customer.orders', ['order' => $payment->order->id]) }}"><i
                                    class="fas fa-link fa-fw mr-1"></i>{{ $payment->order->id }}</x-link>
                        @else
                            <x-link href="{{ route('admin.orders', ['order' => $payment->order->id]) }}"><i
                                    class="fas fa-link fa-fw mr-1"></i>{{ $payment->order->id }}</x-link>
                        @endrole
                    </p>
                    <p><strong>Usuario: </strong> {{ $payment->order->user->name }}</p>
                    <p><strong>Fecha: </strong> {{ $payment->created_at }}</p>
                    <p><strong>Monto Total: </strong> {{ $payment->paid_amount }} Bs</p>
                    <p><strong>Estado: </strong> {{ $payment->status }}</p>
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
