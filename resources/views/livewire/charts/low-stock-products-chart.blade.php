<div>
    <div class="mb-4 flex space-x-4">
        <x-select wire:model.live="limit">
            <option value="3">Top 3</option>
            <option value="5">Top 5</option>
            <option value="10">Top 10</option>
        </x-select>
        <x-spinner wire:loading size="5" />
    </div>

    <div id="lowStockProductsChart" wire:ignore></div>
</div>

@push('scripts')
    <script>
        window.addEventListener('load', function() {
            var lowStockOptions = {
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
                    categories: @json(collect($chartData)->pluck('x')),
                },
                title: {
                    text: 'Productos con Menor Stock',
                    align: 'left'
                },
            };

            var lowStockChart = new ApexCharts(document.querySelector("#lowStockProductsChart"), lowStockOptions);
            lowStockChart.render();

            Livewire.on('update:low-stock', data => {
                lowStockChart.updateSeries([{
                    data: data[0]
                }]);
                lowStockChart.updateOptions({
                    xaxis: {
                        categories: data[0].map(item => item.x)
                    }
                });
            });
        });
    </script>
@endpush
