<div>
    <section class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg my-5 mt-10">
        <header class="flex flex-row justify-between items-center">
            <h3 class="text-xl font-bold mb-5">Ítems</h3>
            <x-button wire:click="create"><x-spinner wire:loading size="5" class="mr-2" /> Nuevo ítem</x-button>
        </header>
        @if ($items->isNotEmpty())
            <div class="hidden sm:block overflow-y-auto" style="max-height: 75vh;">
                <table class="w-full table-auto border-collapse border border-slate-500">
                    <thead>
                        <tr>
                            <x-th>Nombre</x-th>
                            <x-th>Ruta</x-th>
                            <x-th>Icono</x-th>
                            <x-th>Grupo</x-th>
                            <x-th>Estado</x-th>
                            <x-th>Acciones</x-th>
                        </tr>
                    </thead>
                    <tbody class="text-gray:700 dark:text-gray-300">
                        @foreach ($items as $item)
                            <tr>
                                <x-td>{{ $item->name }}</x-td>
                                <x-td>{{ $item->page->route }}</x-td>
                                <x-td class="text-center">
                                    <i class="fa fa-lg {{ $item->icon }} fa-fw" aria-hidden="true"></i>
                                </x-td>
                                <x-td>{{ $item->sidebarGroup->name ?? '' }}</x-td>
                                <x-td>{{ $item->status ? 'Activo' : 'Inactivo' }}</x-td>
                                <x-td class="text-center">
                                    @can('sidebar.edit')
                                        <button class="mx-1" wire:click="edit({{ $item->id }})">
                                            <i class="fa fa-pen fa-fw" aria-hidden="true"></i>
                                        </button>
                                    @endcan
                                    @can('sidebar.delete')
                                        <button class="mx-1" onclick="confirmDeleteItem({{ $item->id }})">
                                            <i class="fa fa-trash fa-fw" aria-hidden="true"></i>
                                        </button>
                                    @endcan
                                </x-td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <h3 class="text-xl mb-5 text-center">No hay páginas registradas</h3>
        @endif
    </section>

    <!-- Modal -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 transform transition-all" x-on:click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto p-6">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-label for="name" value="Nombre" />
                        <x-input id="name" type="text" wire:model="name" placeholder="Crear" required
                            class="block w-full" />
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-label for="page_id" value="Ruta" />
                        <x-select id="page_id" wire:model="page_id" class="block w-full">
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->route }}</option>
                            @endforeach
                        </x-select>
                        @error('route')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <legend class="text-md font-bold col-span-2">Opcional:</legend>
                    <div>
                        <x-label for="icon" value="Ícono (font-awesome)" />
                        <div class="relative">
                            <x-input type="text" id="icon" wire:model.live="icon"
                                class="block w-full" placeholder="fa-home" />
                            <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                                <i class="fas {{$icon}} fa-fw"></i>
                                <x-spinner size="3" wire:loading wire:target="icon"></x-spinner>
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-label for="sidebar_group_id" value="Grupo" />
                        <x-select id="sidebar_group_id" wire:model="sidebar_group_id" class="block w-full">
                            <option value="">
                                Sin grupo
                            </option>
                            @foreach ($sidebarGroups as $sidebarGroup)
                                <option value="{{ $sidebarGroup->id }}">{{ $sidebarGroup->name }}</option>
                            @endforeach
                        </x-select>
                        @error('sidebar_group_id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-label for="permission" value="Permiso" />
                        <x-select id="permission" wire:model="permission" class="block w-full">
                            <option value="">
                                Sin permiso
                            </option>
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                            @endforeach
                        </x-select>
                        @error('permission')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-label for="status" value="Estado" />
                        <x-select id="status" wire:model.defer="status" class="block w-full">
                            <option value="1">
                                Activo
                            </option>
                            <option value="0">
                                Inactivo
                            </option>
                        </x-select>
                        @error('status')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <x-label for="description" value="Descripción" />
                        <x-input id="description" type="text" wire:model.defer="description" class="block w-full"/>
                        @error('description')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4 col-span-2">
                        <x-button-secondary type="button" x-on:click="show = false" class="mx-2">
                            Cancelar
                        </x-button-secondary>
                        <x-button type="submit">
                            Guardar
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
