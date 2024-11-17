<div>
    <section class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg my-5 mt-10">
        <header class="flex flex-row justify-between items-center">
            <h3 class="text-xl font-bold mb-5">Grupos del sidebar</h3>
            <x-button wire:click="create"><x-spinner wire:loading size="5" class="mr-2" /> Nuevo grupo</x-button>
        </header>
        @if (count($groups) > 0)
            <div class="hidden sm:block overflow-y-auto" style="max-height: 75vh;">
                <table class="w-full table-auto border-collapse border border-slate-500">
                    <thead>
                        <tr>
                            <x-th>Nombre</x-th>
                            <x-th>Icono</x-th>
                            <x-th>Descripción</x-th>
                            <x-th>Estado</x-th>
                            <x-th>Acciones</x-th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300">
                        @foreach ($groups as $group)
                            <tr>
                                <x-td>{{ $group->name }}</x-td>
                                <x-td class="text-center">
                                    <i class="fa fa-lg {{ $group->icon }} fa-fw" aria-hidden="true"></i>
                                </x-td>
                                <x-td>{{ $group->description }}</x-td>
                                <x-td>{{ $group->status ? 'Activo' : 'Inactivo' }}</x-td>
                                <x-td class="text-center">
                                    @can('sidebar.edit')
                                        <button class="mx-1" wire:click="edit({{ $group->id }})">
                                            <i class="fa fa-pen fa-fw" aria-hidden="true"></i>
                                        </button>
                                    @endcan
                                    @can('sidebar.delete')
                                        <button class="mx-1" onclick="confirmDeleteGroup({{ $group->id }})">
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
            <h3 class="text-xl mb-5 text-center">No hay grupos registrados</h3>
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
            class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg relative">
            <form wire:submit.prevent="save">
                <div class="px-4 py-5 sm:p-6">
                    <div class="mb-4">
                        <x-label for="name" class="block text-sm font-medium text-gray-700">Nombre</x-label>
                        <x-input type="text" id="name" wire:model="name" class="block w-full" />
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <x-label for="sidebarGroup.icon" value="Ícono (font-awesome)" />
                        <div class="relative">
                            <x-input type="text" id="sidebarGroup.icon" wire:model.live="icon" name="sidebarGroup.icon"
                                class="block w-full" placeholder="fa-home" />
                            <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-4">
                                <i class="fas {{$icon}} fa-fw"></i>
                                <x-spinner size="3" wire:loading wire:target="icon"></x-spinner>
                            </div>
                        </div>

                        @error('icon')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <x-label for="sidebarGroup.description" value="Descripción" />
                        <x-input id="sidebarGroup.description" type="text" wire:model="description"
                            class="block w-full" />
                        @error('description')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <x-label for="sidebarGroup.status" value="Estado" />
                        <x-select id="sidebarGroup.status" wire:model="status" class="block w-full">
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

                    <div class="flex items-center justify-end mt-4">
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
