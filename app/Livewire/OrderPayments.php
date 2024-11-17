<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderPayments extends Component
{
    public $orderId;
    public $payments = [];

    #[On('show-order-payments')]
    public function showPayments($orderId)
    {
        $this->orderId = $orderId;


        $this->payments = Payment::where('order_id', $orderId)->where('status', '!=', 'CANCELADO')->orderBy('created_at', 'desc')->get();

        $this->dispatch('open-modal', 'order-payments');
    }

    public function render()
    {
        return view('livewire.order-payments');
    }
}
