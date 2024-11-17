<div>
    <div class="mb-4 flex space-x-4">
        <x-select wire:model.live="timeRange" class="text-sm w-24">
            <option value="all">Todo el tiempo</option>
            <option value="week">Última semana</option>
            <option value="month">Último mes</option>
            <option value="year">Último año</option>
        </x-select>
        <x-select wire:model.live="sortBy" class="text-sm w-24">
            <option value="sales">Ordenar por ventas</option>
            <option value="name">Ordenar por nombre</option>
        </x-select>
        <x-select wire:model.live="limit" class="text-sm w-24">
            <option value="5">Top 5</option>
            <option value="10">Top 10</option>
            <option value="all">Todas las categorías</option>
        </x-select>
        <x-spinner wire:loading size="5" />
    </div>

    <div id="salesByCategoryChart" wire:ignore></div>
</div>

@push('scripts')
    <script>
        window.addEventListener('load', function() {
            var salesByCategoryOptions = {
                series: @json($chartData->pluck('y')),
                chart: {
                    type: 'pie',
                    height: 350,
                    width: '100%'
                },
                labels: @json($chartData->pluck('x')),
                title: {
                    text: 'Ventas por Categoría',
                    align: 'left'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var salesByCategoryChart = new ApexCharts(document.querySelector("#salesByCategoryChart"),
                salesByCategoryOptions);
            salesByCategoryChart.render();

            Livewire.on('update:sales-category', data => {
                salesByCategoryChart.updateOptions({
                    series: data[0].map(item => item.y),
                    labels: data[0].map(item => item.x)
                });
            });
        });
    </script>
@endpush
