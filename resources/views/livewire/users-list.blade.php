<div>
    <div class="mb-4 flex space-x-5">
        <x-input type="text" wire:model.live="search" placeholder="Buscar usuarios..." class="w-44" />
        <x-spinner wire:loading wire:target="search" size="4" class="mx-4" />

        <x-select wire:model.live="roleFilter">
            <option value="">Todos los roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
        </x-select>
        <x-spinner wire:loading wire:target="roleFilter" size="4" class="mx-4" />
    </div>

    <table class="w-full table table-auto">
        <thead class="border border-light">
            <tr>
                <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('id')">ID</th>
                <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('name')">Nombre</th>
                <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('email')">Email</th>
                <th class="px-4 py-2">Rol</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="border border-light px-3 py-1">{{ $user->id }}</td>
                    <td class="border border-light px-3 py-1">{{ $user->name }}</td>
                    <td class="border border-light px-3 py-1">{{ $user->email }}</td>
                    <td class="border border-light px-3 py-1">{{ $user->getRoleNames()[0] ?? '?' }}
                    </td>
                    <td class="border border-light px-3 py-1">
                        @if ($user->is_banned)
                            <div class="relative">
                                <span
                                    class="peer cursor-pointer rounded-xl bg-red-300 px-4 py-2 font-medium tracking-wide  dark:bg-red-800 dark:text-slate-300"
                                    aria-describedby="tooltipExample">Baneado</span>
                                <div id="tooltipExample"
                                    class="pointer-events-none absolute bottom-full mb-2 left-1/2 -translate-x-1/2 z-10 flex w-64 flex-col gap-1 rounded bg-slate-900 p-2.5 text-sm text-slate-100 opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 dark:bg-white dark:text-slate-900"
                                    role="tooltip">
                                    <span class="text-sm font-medium text-white dark:text-black">Razón</span>
                                    <p class="text-balance">{{ $user->ban_reason }}</p>
                                </div>
                            </div>
                        @else
                            <span>Activo</span>
                        @endif
                    </td>
                    <td class="border border-light px-3 py-1">
                        @can('user.edit')
                            <x-button wire:click="edit({{ $user->id }})" class="py-2" title="Editar">
                                <i class="fas fa-edit fa-fw"></i></x-button>

                            @if ($user->is_banned)
                                <x-button-secondary wire:click="unban({{ $user->id }})" class="py-2"
                                    title="Desbanear">
                                    <i class="fas fa-circle-check fa-fw"></i>
                                </x-button-secondary>
                            @else
                                <x-button-secondary wire:click="banForm({{ $user->id }})" class="py-2" title="Banear">
                                    <i class="fas fa-ban fa-fw"></i>
                                </x-button-secondary>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <x-modal name="rol-form" :show="false" maxWidth="sm">
        <div class="p-6 h-96">
            <form wire:submit.prevent="save" class="text-gray-900">
                <fieldset>
                    <legend class="text-lg font-bold dark:text-white mb-3">
                        Editar rol
                    </legend>
                    <div class="hidden sm:block overflow-y-auto rounded border border-slate-300 px-2 py-1"
                        style="max-height: 35vh;">
                        @foreach ($roles as $index => $role)
                            <div class="flex items-center ps-5 my-2 -py-2 border rounded border-light">
                                <input id="radio.{{ $index }}" type="radio" value="{{ $role->name }}"
                                    name="role" wire:model.defer='role' required
                                    class="w-4 h-4 text-primary bg-gray-100 border-gray-500  focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="radio.{{ $index }}"
                                    class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </fieldset>

                <div class="flex items-center justify-end mt-4">
                    <x-button-secondary type="button" class="mr-2" x-on:click="$dispatch('close')">
                        Cerrar
                        </x-button>
                        <x-button type="submit">
                            Guardar
                        </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="ban-form" :show="false">
        <div class="p-6">
            <form wire:submit.prevent="ban" class="text-gray-900">
                <fieldset class="mt-3">
                    <legend class="text-lg font-bold dark:text-white mb-3">
                        Banear usuario
                    </legend>
                    <x-label value="Razón de la suspención" for="banReason" />
                    <x-textarea wire:model="banReason" id="banReason" rows="3" required></x-textarea>
                </fieldset>

                <div class="flex items-center justify-end mt-4">
                    <x-button-secondary type="button" class="mr-2" x-on:click="$dispatch('close')">
                        Cerrar
                    </x-button-secondary>
                    <x-button type="submit">
                        Bannear
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
