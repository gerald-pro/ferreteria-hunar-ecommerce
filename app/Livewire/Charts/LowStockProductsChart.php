<?php

namespace App\Livewire\Charts;

use App\Services\ChartService;
use Livewire\Component;


class LowStockProductsChart extends Component
{
    public $chartData = [];
    public $limit = 5;
    protected $chartService;

    public function boot(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function mount()
    {
        $this->chartData = $this->chartService->getProductsWithLowStock();
    }

    public function updateChartData()
    {
        $this->chartData = $this->chartService->getProductsWithLowStock($this->limit);
        $this->dispatch('update:low-stock', $this->chartData);
    }

    public function updatedLimit()
    {
        $this->updateChartData();
    }

    public function render()
    {
        return view('livewire.charts.low-stock-products-chart');
    }
}
