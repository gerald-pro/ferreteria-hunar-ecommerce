<x-app-layout>
    <x-slot name="title">
        Paginas
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:text-white">
        @if (session('success'))
            <div class="bg-green-300 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="py-3 px-4 text-green-900">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        <livewire:sidebar-items />
        <livewire:sidebar-groups />
    </div>

    @push('scripts')
        <script>
            function confirmDeleteItem(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    Livewire.dispatch('delete-item', {
                        id: id
                    })
                });
            }

            function confirmDeleteGroup(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    Livewire.dispatch('delete-group', {
                        id: id
                    })
                });
            }
        </script>
    @endpush
</x-app-layout>
