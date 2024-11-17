<?php

namespace App\Livewire;

use App\Exports\PaymentsExport;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PaymentsTable extends DataTableComponent
{
    public $showModal = false;
    public $selectedPayment;
    public $specificPaymentId = null;

    public function mount(Request $request)
    {
        
        if ($request->has('payment')) {
            $this->specificPaymentId = $request->payment;
        }
    }

    public function builder(): Builder
    {
        if (!empty($this->specificPaymentId)) {
            return Payment::query()
                ->where('payments.id', '=', $this->specificPaymentId)
                ->select('payments.*', 'users.name as user_name')
                ->join('orders', 'orders.id', '=', 'payments.order_id')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->orderBy('payments.created_at', 'desc');
        } else {
            return Payment::query()
                ->select('payments.*', 'users.name as user_name')
                ->join('orders', 'orders.id', '=', 'payments.order_id')
                ->join('users', 'orders.user_id', '=', 'users.id');
        }
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setSearchDebounce(1000);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'default' => false,
                'class' => 'text-md',
            ];
        });
    }

    public array $bulkActions = [
        'exportExcel' => 'Exportar Excel',
        'exportPdf' => 'Exportar PDF',
        'exportHtml' => 'Exportar HTML',
    ];

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->searchable()->sortable()
                ->attributes(function ($row) {
                    return [
                        'class' => 'text-lg',
                        'default' => true,
                    ];
                }),

                DateColumn::make('Fecha', 'created_at')
                ->sortable()
                ->outputFormat('d/m/y H:s'),

            Column::make('Usuario', 'order.user.name')
                ->sortable()
                ->searchable(function ($builder, $term) {
                    return $builder->orWhereHas('order.user', function ($query) use ($term) {
                        $query->where('name', 'ILIKE', '%' . $term . '%');
                    });
                }),

            Column::make('Monto (Bs)', 'paid_amount')->sortable()->footer(function ($rows) {
                return 'Subtotal: ' . $rows->sum('paid_amount');
            }),
            Column::make('Estado', 'status')->sortable(),
            Column::make('Acciones')
                ->label(
                    fn ($row, Column $column) => view('components.tables.payment-actions')->with([
                        'payment' => $row,
                    ])
                )->html(),
        ];
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Fecha inicio')->filter(function (Builder $builder, string $value) {
                $builder->where('payments.created_at', '>=', $value);
            }),
            DateFilter::make('Fecha final')->filter(function (Builder $builder, string $value) {
                $builder->where('payments.created_at', '<=', $value);
            }),

            SelectFilter::make('Estado')
                ->options([
                    '' => 'Todos',
                    'PENDIENTE' => 'Pendiente',
                    'PAGADO' => 'Pagado',
                    'CANCELADO' => 'Cancelado',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('payments.status', $value);
                    }
                }),
        ];
    }

    public function exportExcel()
    {
        $payments = $this->getSelected();

        $this->clearSelected();
        return (new PaymentsExport($payments))->download('reporte_pagos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportPdf()
    {
        $payments = $this->getSelected();

        $this->clearSelected();
        return (new PaymentsExport($payments))->download('reporte_pagos.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportHtml()
    {
        $payments = $this->getSelected();

        $this->clearSelected();
        return (new PaymentsExport($payments))->download('reporte_pagos.html', \Maatwebsite\Excel\Excel::HTML);
    }

    #[On('show-details')]
    public function showPaymentModal($paymentId)
    {
        $this->dispatch('show-payment-details', $paymentId);
    }
}
