<x-app-layout>
    <x-slot name="header">
        Ver producto
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                @livewire('product-show', ['id' => $product->id])
            </div>
        </div>
    </div>
</x-app-layout>
