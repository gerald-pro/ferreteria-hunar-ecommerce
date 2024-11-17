<div>
    <div class="flex flex-col sm:flex-row  border border-light rounded">
        <div class="sm:w-1/3 overflow-hidden shadow-sm shadow-slate-400" wire:ignore>
            @if ($product->currentPromotion)
            <div class="relative">
                <div class="absolute top-0 right-0">
                    <div class="w-32 h-8 absolute top-4 -right-8">
                        <div
                            class="h-full w-full bg-rose-600 text-white text-center leading-8 transform rotate-45">
                            PROMOCIÓN
                        </div>
                    </div>
                </div>
            </div>
        @endif
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-lg">
        </div>
        <div class="sm:w-2/3 sm:pl-6">
            <h1 class="text-3xl font-bold mt-3">{{ $product->name }}</h1>
            <p class="mt-4">{{ $product->description }}</p>
            <div class="mt-4">
                @if ($product->currentPromotion)
                    <span class="text-2xl line-through text-red-500">Bs.
                        {{ number_format($product->price, 2) }}</span>
                    <span class="text-2xl font-bold ml-2">Bs.
                        {{ number_format($product->discountedPrice, 2) }}</span>
                    <span class="text-sm bg-emerald-100 text-emerald-700 rounded-full px-2 py-1 ml-2">
                        -{{ $product->currentPromotion->discount_percentage }}%
                    </span>
                @else
                    <span class="text-2xl font-bold">Bs. {{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            <div class="mt-6">
                <x-button wire:click='addToCart({{ $product->id }})'>
                    {{ __('Añadir al carrito') }}
                </x-button>
            </div>
        </div>
    </div>

    <div class="mt-28" wire:ignore>
        <h2 class="text-2xl font-bold">Productos Recomendados</h2>
        <div class="flex flex-row flex-wrap justify-start">
            @foreach ($recommendedProducts as $recommendedProduct)
                <x-product-card :product="$recommendedProduct" />
            @endforeach
        </div>
    </div>
</div>
