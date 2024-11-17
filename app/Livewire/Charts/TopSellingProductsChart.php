<?php

namespace App\Livewire\Charts;

use App\Services\ChartService;
use Livewire\Component;

class TopSellingProductsChart extends Component
{
    public $chartData = [];
    public $limit = 5;
    public $dateRange = 'all';
    protected $chartService;

    public function boot(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function mount()
    {
        $this->chartData = $this->chartService->getTopSellingProducts();
    }
    
    public function updateChartData()
    {
        $this->chartData = $this->chartService->getTopSellingProducts($this->limit, $this->dateRange);
        $this->dispatch('update:top-products', $this->chartData);
    }

    public function updatedLimit()
    {
        $this->updateChartData();
    }

    public function updatedDateRange()
    {
        $this->updateChartData();
    }

    public function render()
    {
        return view('livewire.charts.top-selling-products-chart');
    }
}
