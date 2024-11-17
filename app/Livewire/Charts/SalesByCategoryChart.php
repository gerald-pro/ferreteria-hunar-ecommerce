<?php

namespace App\Livewire\Charts;

use App\Services\ChartService;
use Livewire\Component;

class SalesByCategoryChart extends Component
{
    public $chartData = [];
    public $timeRange = 'all';
    public $sortBy = 'sales';
    public $limit = 10;
    protected $chartService;

    public function boot(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function mount()
    {
        $this->chartData = $this->chartService->getSalesByCategory();
    }

    public function updateChartData()
    {
        $this->chartData = $this->chartService->getSalesByCategory($this->timeRange, $this->sortBy, $this->limit);
        $this->dispatch('update:sales-category', $this->chartData);
    }

    public function updatedTimeRange()
    {
        $this->updateChartData();
    }

    public function updatedSortBy()
    {
        $this->updateChartData();
    }

    public function updatedLimit()
    {
        $this->updateChartData();
    }

    public function render()
    {
        return view('livewire.charts.sales-by-category-chart');
    }
}
