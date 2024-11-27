<div>
    <x-modal name="order-details" :show="false">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Detalles del Pedido
            </h2>

            <div class="mt-4 space-y-4">
                @if ($order)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>ID:</strong> {{ $order->id }}</p>
                            <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Nombre o razón social:</strong> {{ $order->billing_name }}</p>
                            <p><strong>NIT o CI:</strong> {{ $order->billing_id }}</p>
                        </div>
                        <div>
                            <p><strong>Usuario:</strong>
                                <x-link href="{{ route('users.index', ['user' => $order->user->id]) }}">
                                    {{ $order->user->name }}
                                </x-link>
                            </p>
                            <p><strong>Entrega:</strong> {{ $order->delivery_status }}</p>
                            <p><strong>Método de pago:</strong> {{ $order->payment_method }}</p>
                            @php
                                $totalPaid = $order->payments()->where('status', 'PAGADO')->sum('paid_amount');
                            @endphp

                            <p><strong>Pago:</strong> 
                                @if ($totalPaid >= $order->total_amount)
                                PAGADO
                                @else
                                PENDIENTE
                                @endif
                            </p>
                            <p><strong>Monto Total:</strong> {{ $order->total_amount }} Bs</p>
                        </div>
                    </div>

                    <h3 class="mt-6 font-bold text-gray-900 dark:text-gray-100">Productos:</h3>
                    <ul class="list-disc list-inside space-y-2">
                        @foreach ($order->orderItems as $item)
                            <li>
                                <span class="font-medium">{{ $item->product->name }}</span> -
                                Cantidad: {{ $item->quantity }} -
                                Precio: {{ $item->price }} Bs
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="mt-6 flex justify-end">
                <x-button-secondary x-on:click="$dispatch('close')">
                    Cerrar
                </x-button-secondary>
            </div>
        </div>


    </x-modal>
</div>
