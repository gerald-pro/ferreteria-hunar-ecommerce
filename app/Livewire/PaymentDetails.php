<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentDetails extends Component
{
    public $payment;

    #[On('show-payment-details')] 
    public function showDetails($paymentId)
    {
        $this->payment = Payment::find($paymentId);
        
        $this->dispatch('open-modal', 'payment-details');
    }

    public function render()
    {
        return view('livewire.payment-details');
    }
}
