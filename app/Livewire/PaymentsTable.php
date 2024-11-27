<?php

namespace App\Livewire;

use App\Exports\PaymentsExport;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterStatus = '';
    public $startDate = '';
    public $endDate = '';
    public $paymentId = null;

    public function mount(Request $request)
    {
        if ($request->has('payment')) {
            $this->paymentId = $request->payment;
        }
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $payments = Payment::query()
            ->select('payments.*', 'users.name as user_name')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->when($this->paymentId, function ($query) {
                $query->where('payments.id', '=', $this->paymentId);
            })
            ->when($this->search, function (Builder $query) {
                $query->where(function ($query) {
                    $query->where('payments.id', 'like', "%{$this->search}%")
                        ->orWhere('payments.paid_amount', 'like', "%{$this->search}%")
                        ->orWhere('users.name', 'ilike', "%{$this->search}%");
                });
            })
            ->when($this->filterStatus, function (Builder $query) {
                $query->where('payments.status', $this->filterStatus);
            })
            ->when($this->startDate, function (Builder $query) {
                $query->whereDate('payments.created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function (Builder $query) {
                $query->whereDate('payments.created_at', '<=', $this->endDate);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin-payments-list', [
            'payments' => $payments,
        ]);
    }

    public function exportExcel()
    {
        return (new PaymentsExport($this->getSelectedPayments()))->download('reporte_pagos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportPdf()
    {
        return (new PaymentsExport($this->getSelectedPayments()))->download('reporte_pagos.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportHtml()
    {
        return (new PaymentsExport($this->getSelectedPayments()))->download('reporte_pagos.html', \Maatwebsite\Excel\Excel::HTML);
    }

    protected function getSelectedPayments()
    {
        return Payment::query()
            ->select('payments.*', 'users.name as user_name')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->when($this->search, function (Builder $query) {
                $query->where(function ($query) {
                    $query->where('payments.id', 'like', "%{$this->search}%")
                        ->orWhere('payments.paid_amount', 'like', "%{$this->search}%")
                        ->orWhere('users.name', 'ilike', "%{$this->search}%");
                });
            })
            ->when($this->filterStatus, function (Builder $query) {
                $query->where('payments.status', $this->filterStatus);
            })
            ->when($this->startDate, function (Builder $query) {
                $query->whereDate('payments.created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function (Builder $query) {
                $query->whereDate('payments.created_at', '<=', $this->endDate);
            })
            ->get();
    }
}
