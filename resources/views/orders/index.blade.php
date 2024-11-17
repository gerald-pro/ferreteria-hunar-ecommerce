<x-app-layout>
    <x-slot name="title">
        Pedidos
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
            <section class="bg-white dark:bg-gray-800 overflow-auto shadow-sm sm:rounded-lg mb-5">

                <div class="p-6 bg-white dark:bg-gray-800">
                    <header class="flex flex-row justify-between items-center">
                        <h3 class="text-xl font-bold mb-5">Pedidos registrados</h3>
                    </header>

                    @livewire('orders-table')
                </div>
            </section>

            @livewire('order-details')
        </div>
    </div>





    @push('scripts')
        @rappasoftTableScripts
        @rappasoftTableThirdPartyScripts

        <script>
            function confirmComplete(id) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "El pedido se establecerá como completado y se generará el respectivo pago",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('complete-order', {
                            orderId: id
                        });
                    }
                })
            }

            function confirmCancel(id) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "El pedido se establecerá como cancelado y se modificará el respectivo pago",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('cancel-order', {
                            orderId: id
                        });
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>
