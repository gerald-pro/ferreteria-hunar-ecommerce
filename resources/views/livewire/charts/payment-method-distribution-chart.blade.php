<div>
    <div class="mb-4 flex space-x-4">
        <x-select wire:model.live="timeRange" class="text-sm">
            <option value="all">Todo el tiempo</option>
            <option value="week">Última semana</option>
            <option value="month">Último mes</option>
            <option value="year">Último año</option>
        </x-select>
        <x-select wire:model.live="sortBy" class="text-sm">
            <option value="count">Ordenar por cantidad</option>
            <option value="name">Ordenar por nombre</option>
        </x-select>

        <x-spinner wire:loading size="5" />
    </div>
    <div id="paymentMethodDistributionChart" wire:ignore></div>
</div>

@push('scripts')
    <script>
       window.addEventListener('load', function() {
            var paymentMethodDistributionOptions = {
                series: @json($chartData->pluck('y')),
                chart: {
                    type: 'donut',
                    height: 350,
                    zoom: {
                        enabled: true
                    },
                    width: '100%',
                    toolbar: {
                        show: true
                    },
                },
                labels: @json($chartData->pluck('x')),
                title: {
                    text: 'Distribución de Métodos de Pago',
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

            var paymentMethodDistributionChart = new ApexCharts(document.querySelector(
                    "#paymentMethodDistributionChart"),
                paymentMethodDistributionOptions);
            paymentMethodDistributionChart.render();

            Livewire.on('update:payment-distribution', data => {
                paymentMethodDistributionChart.updateOptions({
                    series: data[0].map(item => item.y),
                    labels: data[0].map(item => item.x)
                });
            });
        });
    </script>
@endpush
