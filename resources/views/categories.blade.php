<x-app-layout>
    <x-slot name="header">
        Categorias de productos
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
            <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <header class="flex flex-row justify-between items-center">
                    <h3 class="text-xl font-bold mb-5">Categorias de productos</h3>
                </header>

                @livewire('category-list')
            </div>
        </div>
    </div>
</x-app-layout>
