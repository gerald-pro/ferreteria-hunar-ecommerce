@props([
    'align' => 'right',
])

<div class="relative inline-flex" x-data="{ open: false }">
    <button
        class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600/80 rounded-full"
        :class="{ 'bg-slate-200': open }" aria-haspopup="true" @click.prevent="open = !open" :aria-expanded="open">
        <span class="sr-only">Info</span>

        <i class="fas fa-brush fa-lg fa-fw"></i>
    </button>
    <div class="origin-top-right z-10 absolute top-full min-w-44 bg-white dark:bg-skin-sidebar-dark border border-slate-200 dark:border-slate-700 py-1.5 rounded shadow-lg overflow-hidden mt-1 {{ $align === 'right' ? 'right-0' : 'left-0' }}"
        @click.outside="open = false" @keydown.escape.window="open = false" x-show="open"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" x-cloak>
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase pt-1.5 pb-2 px-3">temas
        </div>

        <ul class="grid w-full gap-2 px-2 py-1 text-gray-700">
            <li>
                <input type="radio" id="option-enfant" name="theme-options" value="enfant" class="hidden peer">
                <label for="option-enfant"
                    class="inline-flex items-center justify-between w-full py-2 px-3  bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="w-full font-semibold">Niño</div>
                    </div>
                </label>
            </li>
            <li>
                <input type="radio" id="option-jeune" name="theme-options" value="jeune" class="hidden peer">
                <label for="option-jeune"
                    class="inline-flex items-center justify-between w-full py-2 px-3 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="w-full font-semibold">Joven</div>
                    </div>
                </label>
            </li>
            <li>
                <input type="radio" id="option-adult" name="theme-options" value="adult" class="hidden peer">
                <label for="option-adult"
                    class="inline-flex items-center justify-between w-full py-2 px-3 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="w-full font-semibold">Adulto</div>
                    </div>
                </label>
            </li>
        </ul>
    </div>
</div>
