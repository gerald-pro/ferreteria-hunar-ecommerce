<x-app-layout>
    <x-slot name="title">
        Paginas
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
        @if (session('success'))
            <div class="bg-green-300 overflow-hidden shadow-sm sm:rounded-lg mb-5">
                <div class="py-3 px-4 text-green-900">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <section class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg my-5 mt-10">
            <header class="bg-white dark:bg-gray-800">
                <h3 class="text-xl font-bold mb-5">Paginas visitadas</h3>
            </header>

            <div class="hidden sm:block overflow-y-auto" style="max-height: 75vh;">
                <table class="w-full table-auto border-collapse border border-slate-500">
                    <thead>
                        <tr>
                            <x-th>Ruta</x-th>
                            <x-th>Visitas</x-th>
                        </tr>
                    </thead>
                    <tbody class="text-gray:700 dark:text-gray-300">

                        @foreach ($pages as $page)
                            <tr>
                                <x-td>
                                    {{ $page->route }}
                                </x-td>
                                <x-td>
                                    {{ $page->visits }}
                                </x-td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @livewireScripts

</x-app-layout>
