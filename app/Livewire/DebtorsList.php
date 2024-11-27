<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DebtorsList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'total_debt';
    public $sortDirection = 'desc';
    public $selectedDebtor;
    public $showDetailModal = false;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $debtors = User::query()
            ->whereHas('orders', function ($query) {
                $query->where(function ($query) {
                    // Filtrar pedidos donde los pagos completados sean menores que el monto total
                    $query->whereRaw('
                (SELECT COALESCE(SUM(paid_amount), 0) 
                 FROM payments 
                 WHERE payments.order_id = orders.id AND payments.status = \'PAGADO\') < orders.total_amount
            ');
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'ilike', '%' . $this->search . '%');
                });
            })
           /*  ->withCount(['orders' => function ($query) {
                $query->where(function ($query) {
                    $query->whereRaw('
                (SELECT COALESCE(SUM(paid_amount), 0) 
                 FROM payments 
                 WHERE payments.order_id = orders.id AND payments.status = \'PAGADO\') < orders.total_amount
            ');
                });
            }]) */
            ->withSum(['orders as total_debt' => function ($query) {
                $query->where(function ($query) {
                    $query->whereRaw('
                (SELECT COALESCE(SUM(paid_amount), 0) 
                 FROM payments 
                 WHERE payments.order_id = orders.id AND payments.status = \'PAGADO\') < orders.total_amount
            ');
                });
            }], 'total_amount')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.debtors-list', [
            'debtors' => $debtors,
        ]);
    }

    public function showDetail($userId)
    {
        $this->selectedDebtor = User::with(['orders' => function ($query) {
            $query->doesntHave('payments')
                // Pedidos con pagos pendientes
                ->orWhereHas('payments', function ($query) {
                    $query->where('status', 'PENDIENTE');
                })->with(['payments' => function ($query) {
                    $query->where('status', 'PENDIENTE');
                }]);
        }])->findOrFail($userId);

        $this->showDetailModal = true;
        $this->dispatch('open-modal', 'debtor-detail');
    }
}
