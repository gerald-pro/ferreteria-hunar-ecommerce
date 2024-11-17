<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mensajes de contacto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
            <section class="p-6 bg-white dark:bg-gray-800 overflow-auto shadow-sm sm:rounded-lg mb-5">
                <header class="flex flex-row justify-between items-center">
                    <h3 class="text-xl font-bold mb-5">Mensajes de contacto</h3>
                </header>
                <livewire:contacts.contact-messages-list />
            </section>
        </div>
    </div>
</x-app-layout>
