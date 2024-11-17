<div>
    <x-button wire:click="dispatch('show-order-details', { orderId: {{ $order->id }} })">
        <i class="fas fa-eye fa-fw"></i>
    </x-button>
    @if ($order->delivery_status === 'PENDIENTE')
        <x-button onclick="confirmComplete({{ $order->id }})" class="bg-emerald-600 hover:bg-emerald-700">
            <i class="fas fa-check fa-fw"></i>
        </x-button>
    @endif
    @if ($order->delivery_status == 'PENDIENTE')
        <x-button onclick="confirmCancel({{ $order->id }})" class="bg-red-600 hover:bg-red-700">
            <i class="fas fa-x fa-fw"></i>
        </x-button>
    @endif
</div>
