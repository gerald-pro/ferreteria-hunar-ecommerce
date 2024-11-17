<x-app-layout>
    <x-slot name="title">
        Nota de venta
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
            <section class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-5">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <header class="flex flex-row justify-between items-center">
                        <h3 class="text-xl font-bold mb-5">Nota de venta</h3>
                    </header>

                    <div class="grid grid-cols-4 pb-3">
                        <div class="col">
                            <x-label>
                                Nro de transacción:
                            </x-label>
                            <p>{{ $saleNote->nro_transaction }}</p>
                        </div>
                        <div class="col">
                            <x-label>
                                Código:
                            </x-label>
                            <p>{{ $saleNote->sale_code }}</p>
                        </div>
                        <div class="col">
                            <x-label>
                                Comprador:
                            </x-label>
                            <p>{{ $saleNote->user->name }}</p>
                        </div>
                        <div class="col">
                            <x-label>
                                Estado:
                            </x-label>
                            <p>{{ $saleNote->status == 1 ? 'Completado' : 'No completado' }}</p>
                        </div>
                    </div>

                    @if ($saleNote->saleDetails)
                        <table class="w-full table-auto border-collapse border border-slate-500">
                            <thead>
                                <tr>
                                    <x-th>Articulo</x-th>
                                    <x-th>Precio</x-th>
                                </tr>
                            </thead>
                            <tbody class="text-gray:700 dark:text-gray-300">
                                @foreach ($saleNote->saleDetails as $detail)
                                    <tr>
                                        <x-td>
                                            <x-link
                                                href="{{ route('articles.show', $detail->article->slug) }}">{{ $detail->article->title }}</x-link>
                                        </x-td>
                                        <x-td>
                                            {{ $detail->amount }}
                                        </x-td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold">
                                    <x-td>
                                        Total (bs)
                                    </x-td>
                                    <x-td>
                                        {{ $saleNote->total }}
                                    </x-td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
