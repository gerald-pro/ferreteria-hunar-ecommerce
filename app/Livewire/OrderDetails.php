<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderDetails extends Component
{
    public $order;

    #[On('show-order-details')] 
    public function showOrderDetails($orderId)
    {
        $this->order = Order::with(['user', 'orderItems.product' => function ($query) {
            $query->withTrashed();
        }])->findOrFail($orderId);
        
        $this->dispatch('open-modal', 'order-details');
    }

    public function render()
    {
        return view('livewire.order-details');
    }
}
