<div>
    <div class="mb-4 flex space-x-4">
        <x-select wire:model.live="limit">
            <option value="5">Top 5</option>
            <option value="10">Top 10</option>
            <option value="20">Top 20</option>
        </x-select>
        <x-select wire:model.live="dateRange">
            <option value="all">Todo el tiempo</option>
            <option value="week">Última semana</option>
            <option value="month">Último mes</option>
            <option value="year">Último año</option>
        </x-select>

        <x-spinner wire:loading size="5" />
    </div>
    <div id="topSellingProductsChart" wire:ignore></div>
</div>

@push('scripts')
    <script>
        window.addEventListener('load', function() {
            var topSellingProductsOptions = {
                series: [{
                    data: @json($chartData)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    zoom: {
                        enabled: true
                    },
                    width: '100%',
                    toolbar: {
                        show: true
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($chartData->pluck('x')),
                },
                title: {
                    text: 'Productos más vendidos',
                    align: 'left'
                },
            };

            var topSellingProductsChart = new ApexCharts(document.querySelector("#topSellingProductsChart"),
                topSellingProductsOptions);
            topSellingProductsChart.render();

            Livewire.on('update:top-products', data => {
                topSellingProductsChart.updateSeries([{
                    data: data[0]
                }]);
                topSellingProductsChart.updateOptions({
                    xaxis: {
                        categories: data[0].map(item => item.x)
                    }
                });
            });
        });
    </script>
@endpush
