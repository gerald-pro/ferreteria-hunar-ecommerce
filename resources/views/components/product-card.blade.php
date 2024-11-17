<product
    class="w-80 sm:w-60 bg-white rounded-lg shadow-md dark:bg-gray-800 m-4 my-5 flex flex-col flex-grow overflow-hidden">
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

    <header>
        <a href="{{ route('products.show', $product) }}">
            <span class="rounded-t-lg featured-post-image" role="img"
                style="background-image: url('{{ $product->image_url }}');" alt="Featured Image"></span>
        </a>
    </header>
    <div class="p-5 h-full flex flex-col">
        <a href="{{ route('products.show', $product) }}">
            <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white break-words">
                {{ $product->name }}
            </h3>
        </a>

        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 overflow-hidden text-ellipsis">
            {{ $product->description }}
        </p>

        <div class="mt-auto flex flex-col space-y-2">
            <a href="{{ route('products.show', $product) }}"
                class="w-full inline-flex items-center justify-center py-2 px-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Ver
                <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </a>
            @if ($product->price || $product->price > 0)
                <button wire:click='addToCart({{ $product->id }})'
                    class="w-full inline-flex items-center justify-center py-2 px-3 text-sm font-medium text-center text-white bg-button-primary rounded-lg hover:bg-button-primary-hover focus:ring-4">
                    {{ __('Añadir al carrito') }}
                </button>

                <div class="w-full flex justify-between items-center">
                    @if ($product->currentPromotion)
                        <span class="text-md line-through text-red-700">Bs. {{ $product->price }}</span>
                        <span class="text-lg font-bold">Bs.
                            {{ number_format($product->discountedPrice, 2) }}</span>
                        <span class="text-sm bg-emerald-100 text-emerald-700 rounded-full px-2 py-1">
                            -{{ $product->currentPromotion->discount_percentage }}%
                        </span>
                    @else
                        <span class="text-lg">Bs. {{ $product->price }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</product>
