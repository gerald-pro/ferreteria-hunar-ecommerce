<?php

namespace App\Livewire;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersTable extends Component
{
    use WithPagination;

    public $id;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
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

    public function mount(Request $request)
    {
        if ($request->has('order')) {
            $this->id = $request->order;
        }
    }

    public function render()
    {
        $orders = Order::with('user')
            ->when($this->id, function ($query) {
                $query->where('id', '=', $this->id);
            })
            ->when($this->search, function (Builder $query) {
                $query->where('id', 'like', "%{$this->search}%")
                    ->orWhere('total_amount', 'like', "%{$this->search}%")
                    ->orWhereHas('user', function (Builder $userQuery) {
                        $userQuery->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->when($this->filterStatus, function (Builder $query) {
                $query->where('delivery_status', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin-orders-list', [
            'orders' => $orders,
        ]);
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
