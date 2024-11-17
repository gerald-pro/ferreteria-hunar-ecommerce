<?php

namespace App\Livewire\Charts;

use App\Services\ChartService;
use Livewire\Component;

class SalesOverTimeChart extends Component
{
    public $timeRange = 'week';
    public $chartData = [];

    public function mount()
    {
        $this->updateChartData();
    }

    public function updateChartData()
    {
        $this->chartData = (new ChartService)->getSalesOverTime($this->timeRange);
    }

    public function updating($property, $value)
    {
        if ($property === 'timeRange') {
            if ($this->timeRange != $value) {
                $this->timeRange = $value;
                $newData = (new ChartService)->getSalesOverTime($this->timeRange);
                $this->chartData = $newData ;
                $this->dispatch('update:sales-over-time', $newData);
            }
        }
    }

    public function render()
    {
        return view('livewire.charts.sales-over-time-chart');
    }
}
