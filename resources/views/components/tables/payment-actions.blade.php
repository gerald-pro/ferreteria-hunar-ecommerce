<div>
    <x-button wire:click="dispatch('show-payment-details', { paymentId: {{ $payment->id }} })">
        <i class="fas fa-eye fa-fw"></i>
    </x-button>
</div>
