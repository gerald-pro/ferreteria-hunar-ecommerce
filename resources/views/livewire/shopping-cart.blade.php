<div>
    <header class="flex flex-row justify-between items-center">
        <h3 class="text-xl font-bold mb-5">Carrito de compras</h3>
    </header>

    <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">
        <div class="md:w-2/3 col gap-4">
            @foreach ($content as $id => $item)
                <div class="overflow-hidden border border-light my-auto" x-data="{
                    quantity: @entangle('content.' . $id . '.quantity'),
                    original_price: @entangle('content.' . $id . '.original_price'),
                    discounted_price: @entangle('content.' . $id . '.discounted_price'),
                    discount_percentage: @entangle('content.' . $id . '.discount_percentage'),
                    price: @entangle('content.' . $id . '.price')
                }">
                    <div class="p-4 flex justify-between items-center">
                        <div class="flex items-center">
                            <div>
                                <p class="font-bold">{{ $item['name'] }}</p>
                                <template x-if="discount_percentage > 0">
                                    <div>
                                        <p class="text-gray-500 line-through">Precio original: <span
                                                x-text="original_price"></span></p>
                                        <p class="text-green-600">Descuento: <span x-text="discount_percentage"></span>%
                                        </p>
                                        <p>Precio final: <span x-text="discounted_price.toFixed(2)"></span></p>
                                    </div>
                                </template>
                                <template x-if="discount_percentage == 0">
                                    <p>Precio: <span x-text="price.toFixed(2)"></span></p>
                                </template>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <x-button-secondary class="focus:outline-none focus:text-gray-600"
                                @click="if(quantity > 1) { quantity--; $wire.decreaseQuantity('{{ $id }}'); }">
                                <span class="fas fa-minus"></span>
                            </x-button-secondary>
                            <span class="mx-2" x-text="quantity"></span>
                            <x-button-secondary class="focus:outline-none focus:text-gray-600"
                                @click="quantity++; $wire.increaseQuantity('{{ $id }}');">
                                <span class="fas fa-plus"></span>
                            </x-button-secondary>
                        </div>
                        <div>
                            <p>Subtotal: <span x-text="(price * quantity).toFixed(2)"></span></p>
                        </div>
                        <x-button-secondary
                            class="text-red-600 hover:text-red-700 focus:outline-none focus:text-red-700"
                            wire:click="removeFromCart('{{ $id }}')">
                            <span class="fas fa-x"></span>
                        </x-button-secondary>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6 h-full border border-light p-6 shadow-md md:mt-0 md:w-1/3" x-data="{ total: @entangle('total') }">
            <p class="text-xl text-right mb-2">Total: Bs. <span x-text="total.toFixed(2)"></span></p>
            <x-spinner size=4 wire:loading />
            <form id="orderForm" onsubmit="event.preventDefault(); confirmOrder();">
               {{--  <div class="mb-4">
                    <x-label for="paymentMethod" class="block mb-2">Método de pago:</x-label>
                    <x-select wire:model="paymentMethod" id="paymentMethod" class="w-full p-2 border rounded" required>
                        <option value="ELECTRONICO">Pago electrónico</option>
                        <option value="CONTRA_ENTREGA">Pago contra entrega</option>
                    </x-select>
                </div> --}}

                <div class="mb-4">
                    <x-label for="deliveryAddress" class="block mb-2">Dirección de entrega:</x-label>
                    <x-textarea wire:model="deliveryAddress" id="deliveryAddress" class="w-full p-2 border rounded"
                        rows="3" required></x-textarea>
                </div>

                <div class="mb-4">
                    <x-label for="billingName" class="block mb-2">Nombre o Razón Social:</x-label>
                    <x-input wire:model="billingName" id="billingName" type="text" class="w-full p-2 border rounded"
                        required />
                </div>

                <div class="mb-4">
                    <x-label for="billingId" class="block mb-2">Número de identificación o NIT:</x-label>
                    <x-input wire:model="billingId" id="billingId" type="number" class="w-full p-2 border rounded"
                        required />
                </div>

                <div class="grid grid-cols-2 justify-content-between gap-2">
                    <div class="col">
                        <x-button-secondary class="w-full justify-center" type="button"
                            wire:click="clearCart">Limpiar</x-button-secondary>
                    </div>

                    <div class="col">
                        @auth
                            <x-button type="submit" class="w-full justify-center" wire:loading.attr="disabled">
                                <div wire:loading
                                    class="inline-block h-3 w-3 my-1 animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                    role="status">
                                </div>
                                <span wire:loading.remove>Pedido</span>
                            </x-button>
                        @else
                            <x-button class="w-full justify-center" type="button">
                                <a href="{{ route('login') }}">Pedido</a>
                            </x-button>
                        @endauth
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmOrder() {
                const form = document.getElementById('orderForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                if (@this.content.length === 0) {
                    Swal.fire({
                        title: 'Error',
                        text: "El carrito está vacío. No se puede crear un pedido.",
                        icon: 'info',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Entendido',
                    });
                    return;
                }
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Se generará el pedido para el carrito de compras actual",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.dispatch('create-order');

                    }
                })
            }
        </script>
    @endpush

</div>
