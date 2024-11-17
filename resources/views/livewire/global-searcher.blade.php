<div>
    <div class="relative">
        <label for="modal-search" class="sr-only">Buscar</label>
        <input id="modal-search" wire:model.live.debounce.400ms="query" x-ref="searchInput"
            class="w-full dark:text-slate-300 bg-white dark:bg-slate-800 border-0 focus:ring-transparent placeholder-slate-400 dark:placeholder-slate-500 appearance-none py-3 pl-10 pr-4"
            type="search" placeholder="Buscar algo…" />
        <button class="absolute inset-0 right-auto group" type="submit" aria-label="Search">
            <i class="fas fa-search fa-fw w-4 h-4 ml-4 mr-2"></i>
        </button>
    </div>
    <div class="py-4 px-2">
        <!-- Results -->
        <div class="mb-3 last:mb-0">
            @if (!empty($results) && count($results) > 0)
                <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase px-2 mb-2">
                    Resultados
                </div>

                <ul class="text-sm">
                    @foreach ($results as $result)
                        <li><a class="flex items-center p-2 text-slate-800 dark:text-slate-100 hover:text-white hover:bg-indigo-500 rounded group"
                                href="{{ $result['url'] }}">{{ $result['title'] }}</a></li>
                    @endforeach
                </ul>
            @endif
            <div wire:loading class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase px-2 mb-2">
                Buscando...
            </div>
        </div>
    </div>
</div>
