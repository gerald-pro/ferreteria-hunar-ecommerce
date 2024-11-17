<div>
    <div class="mb-4 flex items-center justify-end">
        @can('role.create')
            <x-button wire:click="create">
                <x-spinner wire:loading wire:target="create" size="4" class="mx-4 my-1" />
                <span wire:loading.remove wire:target='create'>Añadir</span>
            </x-button>
        @endcan
    </div>
    <table class="w-full table table-auto">
        <thead class="border border-light">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td class="border border-light px-3 py-1">{{ $role->id }}</td>
                    <td class="border border-light px-3 py-1">{{ $role->name }}</td>
                    <td class="border border-light px-3 py-1">
                        @can('role.edit')
                            <x-button wire:click="edit({{ $role->id }})" class="py-2" title="Editar">
                                <x-spinner wire:loading wire:target="edit({{ $role->id }})" size="3" />
                                <span wire:loading.remove wire:target='edit({{ $role->id }})'
                                    class="fas fa-edit"></span>
                            </x-button>
                        @endcan
                        @can('role.delete')
                            <x-button-secondary onclick="confirm({{ $role->id }})" class="py-2" title="Eliminar">
                                <i class="fas fa-trash fa-fw"></i>
                            </x-button-secondary>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-modal name="role-form" :show="false">
        <div class="p-6 min-h-96">
            <div x-data="{
                selectedPermissions: @entangle('permissions'),
                init() {
                    let tomSelect = new TomSelect(this.$refs.permissions, {
                        plugins: {
                            remove_button: {
                                title: 'Eliminar permiso',
                            }
                        },
                        onChange: (values) => {
                            this.selectedPermissions = values;
                        }
                    });
            
                    this.$watch('selectedPermissions', value => {
                        if (JSON.stringify(tomSelect.getValue()) !== JSON.stringify(value)) {
                            tomSelect.setValue(value, true);
                        }
                    });
            
                    $wire.on('roleUpdated', () => {
                        tomSelect.setValue(this.selectedPermissions, true);
                    });
                },
                submit() {
                    $wire.set('permissions', this.selectedPermissions, true);
                }
            }">
                <form id="roleForm" wire:submit.prevent="save">

                    <h2 class="text-lg font-medium">{{ $roleId ? 'Editar' : 'Crear' }} rol</h2>
                    <div class="py-6">
                        <x-label for="role.name" value="Nombre" />
                        <x-input id="role.name" type="text" wire:model="name" placeholder="Nombre del rol"
                            required />
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror

                    </div>
                    <div py-6 wire:ignore>
                        <x-label for="permissions" value="Permisos" />
                        <x-select class="w-full block" id="permissions" x-ref="permissions" multiple>
                            <option value="">Seleccionar permisos</option>
                            @foreach ($allPermissions as $item)
                                <option value="{{ $item->name }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </x-select>

                        @error('permissions')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-6 flex justify-end">
                        <x-button-secondary type="button" class="mr-2"
                            x-on:click="$dispatch('close')">Cancelar</x-button-secondary>

                        <x-button type="submit">
                            <x-spinner wire:loading wire:target="save" size="4" class="mx-6" />
                            <span wire:loading.remove wire:target='save' x-on:click="submit">Guardar</span>
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>
</div>



@push('scripts')
    <script>
        function confirm(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', {
                        roleId: id
                    });
                }
            })
        }
    </script>
@endpush
