<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ClientOrdersList extends Component
{
    use WithPagination;

    public $id;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $qrImage;
    public $showQrModal = false;
    public $showPaymentModal = false;
    public $filterStatus = '';
    public $currentOrderId;
    public $paymentAmount;
    public $maxPaymentAmount = 0;

    protected $rules = [
        'paymentAmount' => 'required|numeric|min:0',
    ];


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updateTransactions()
    {
        PaymentService::updatePendingTransactions(Auth::user()->id);
        $this->dispatch('toast:message', [
            'message' =>  'Pedidos actualizados',
            'status' => 'success',
        ]);
    }

    public function mount(Request $request)
    {
        if ($request->has('order')) {
            $this->id = $request->order;
        }
    }

    public function render()
    {
        $orders = Order::where('user_id', auth()->id())
            ->when($this->id, function ($query) {
                $query->where('id', '=', $this->id);
            })
            ->where(function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('total_amount', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function (Builder $query) {
                $query->where('delivery_status', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.client-orders-list', [
            'orders' => $orders
        ]);
    }

    public function showPayment($orderId)
    {
        $order = Order::findOrFail($orderId);
        $this->currentOrderId = $orderId;

        $totalPaid = $order->payments()
            ->where('status', 'PAGADO')
            ->sum('paid_amount');

        $this->maxPaymentAmount = $order->total_amount - $totalPaid;
        $this->paymentAmount = $this->maxPaymentAmount;

        $this->dispatch('open-modal', 'payment-modal');
    }

    public function processPayment()
    {
        $this->validate();

        if ($this->paymentAmount <= 0 || $this->paymentAmount > $this->maxPaymentAmount) {
            $this->dispatch('swal:modal', [
                'title' => 'Error!',
                'text' => 'Monto de pago inválido',
                'icon' => 'error',
            ]);
            return;
        }

        try {
            $response = PaymentService::processPayment($this->currentOrderId, $this->paymentAmount);

            if ($response['status'] === 'success') {
                $order = Order::with('user', 'orderItems.product')->findOrFail($this->currentOrderId);
                $this->qrImage = $order->payments()->latest()->first()->qr_image;
                $this->showPaymentModal = false;

                $this->dispatch('close-modal', 'payment-modal');
                $this->dispatch('open-modal', 'qr-modal');
            } else {
                $this->dispatch('swal:modal', [
                    'title' => 'Pago!',
                    'text' => $response['message'],
                    'icon' => 'error',
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'title' => 'Pago!',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }
}
