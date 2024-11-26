<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estadisticas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
            <section class="bg-white dark:bg-gray-800 overflow-auto shadow-sm sm:rounded-lg mb-5">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <header class="flex flex-row justify-between items-center">
                        <h3 class="text-xl font-bold mb-5">Estadisticas</h3>
                    </header>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-7">
                        <div><livewire:charts.sales-over-time-chart /></div>

                        <div><livewire:charts.top-selling-products-chart /></div>

                        <div> <livewire:charts.sales-by-category-chart /></div>

                        <div><livewire:charts.low-stock-products-chart /></div>
                        {{-- <div><livewire:charts.payment-method-distribution-chart /></div> --}}
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
