<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ClientPaymentsList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $qrImage;
    public $showQrModal = false;
    public $status = '';

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
        if ($request->has('payment')) {
            $this->search = $request->payment;
        }
    }

    public function render()
    {
        $payments = Payment::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->where(function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('paid_amount', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.client-payments-list', [
            'payments' => $payments
        ]);
    }

    public function updateTransactions()
    {
        PaymentService::updatePendingTransactions(Auth::user()->id);
        $this->dispatch('toast:message', [
            'message' =>  'Pedidos actualizados',
            'status' => 'success',
        ]);
    }

    public function payOrder($paymentId)
    {
        try {
            $payment = Payment::findOrFail($paymentId);
            $this->qrImage = $payment->qr_image;
            $this->dispatch('open-modal', 'qr-modal');
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'title' => 'Pago!',
                'text' =>  $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }
}
