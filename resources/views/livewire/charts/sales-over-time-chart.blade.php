<div>
    <div class="flex mb-4">
        <x-select wire:model.live="timeRange">
            <option value="week">Última semana</option>
            <option value="month">Último mes</option>
            <option value="year">Último año</option>
        </x-select>
        <x-spinner wire:loading size="5" />
    </div>
    <div id="salesOverTimeChart" wire:ignore>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('load', function() {
            var salesOverTimeOptions = {
                series: [{
                    name: 'Ventas',
                    data: @json($chartData)
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    zoom: {
                        enabled: true
                    },
                    width: '100%',
                    toolbar: {
                        show: true
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                title: {
                    text: 'Tendencia de Ventas',
                    align: 'left'
                },
                xaxis: {
                    type: 'datetime',
                },
                yaxis: {
                    title: {
                        text: 'Ventas (Bs)'
                    },
                },
            };

            var salesOverTimeChart = new ApexCharts(document.querySelector("#salesOverTimeChart"),
                salesOverTimeOptions);
            salesOverTimeChart.render();

            Livewire.on('update:sales-over-time', data => {
                salesOverTimeChart.updateSeries([{
                    name: 'Ventas',
                    data: data[0]
                }]);
            });
        });
    </script>
@endpush
