<?php

namespace App\Livewire\Charts;

use App\Services\ChartService;
use Livewire\Component;

class PaymentMethodDistributionChart extends Component
{
    public $chartData = [];
    public $timeRange = 'all';
    public $sortBy = 'count';
    protected $chartService;

    public function boot(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function mount()
    {
        $this->chartData = $this->chartService->getPaymentMethodDistribution();
    }

    public function updateChartData()
    {
        $this->chartData = $this->chartService->getPaymentMethodDistribution($this->timeRange, $this->sortBy);
        $this->dispatch('update:payment-distribution', $this->chartData);
    }

    public function updatedTimeRange()
    {
        $this->updateChartData();
    }

    public function updatedSortBy()
    {
        $this->updateChartData();
    }

    public function render()
    {
        return view('livewire.charts.payment-method-distribution-chart');
    }
}
