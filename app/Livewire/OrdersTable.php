<?php

namespace App\Livewire;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrdersTable extends DataTableComponent
{
    public $showModal = false;
    public $specificOrderId = null;


    public function mount(Request $request)
    {
        if ($request->has('order')) {
            $this->specificOrderId = $request->order;
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
            Column::make('ID', 'id')->searchable()->sortable(),
            DateColumn::make('Fecha', 'created_at')
                ->sortable()
                ->outputFormat('d/m/y H:s'),

            Column::make('Usuario', 'user.name')
                ->sortable()
                ->searchable(function ($builder, $term) {
                    return $builder->orWhere('user.name', 'ILIKE', '%' . $term . '%');
                }),

            Column::make('Monto (Bs)', 'total_amount')->sortable()->footer(function ($rows) {
                return 'Subtotal: ' . $rows->sum('total_amount');
            }),

            Column::make('Estado', 'delivery_status')->sortable(),
            Column::make('Acciones')
                ->label(
                    fn ($row, Column $column) => view('components.tables.order-actions')->with([
                        'order' => $row,
                    ])
                )->html(),
        ];
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Fecha inicio')->filter(function (Builder $builder, string $value) {
                $builder->where('orders.created_at', '>=', $value);
            }),
            DateFilter::make('Fecha final')->filter(function (Builder $builder, string $value) {
                $builder->where('orders.created_at', '<=', $value);
            }),

            SelectFilter::make('Estado')
                ->options([
                    '' => 'Todos',
                    'PENDIENTE' => 'Pendiente',
                    'COMPLETADO' => 'Completado',
                    'CANCELADO' => 'Cancelado',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('orders.delivery_status', $value);
                    }
                }),
        ];
    }

    public function builder(): Builder
    {
        if (!empty($this->specificOrderId)) {
            return Order::query()
                ->where('orders.id', '=', $this->specificOrderId)
                ->select('orders.*', 'users.name as user_name')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->orderBy('orders.created_at', 'desc');
        } else {
            return Order::query()
                ->select('orders.*', 'users.name as user_name')
                ->join('users', 'orders.user_id', '=', 'users.id');
        }
    }

    public function exportExcel()
    {
        $orders = $this->getSelected();

        $this->clearSelected();
        return (new OrdersExport($orders))->download('reporte_pedidos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportPdf()
    {
        $orders = $this->getSelected();

        $this->clearSelected();
        return (new OrdersExport($orders))->download('reporte_pedidos.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportHtml()
    {
        $orders = $this->getSelected();

        $this->clearSelected();
        return (new OrdersExport($orders))->download('reporte_pedidos.html', \Maatwebsite\Excel\Excel::HTML);
    }

    #[On('show-details')]
    public function showOrderModal($orderId)
    {
        $this->dispatch('show-order-details', $orderId);
    }

    #[On('complete-order')]

    public function completeOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            (new OrderService())->completeOrder($order);
            $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Pedido completado con éxito.']);
        } catch (\Exception $e) {
            $this->dispatch('toast:message', ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    #[On('cancel-order')]
    public function cancelOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            (new OrderService())->cancelOrder($order);
            $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Pedido cancelado con éxito.']);
        } catch (\Exception $e) {
            $this->dispatch('toast:message', ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
