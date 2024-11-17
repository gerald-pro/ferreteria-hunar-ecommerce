<div>
    <table class="min-w-full border border-light">
        <thead>
            <tr>
                <th class="w-1/12 px-4 py-2">ID</th>
                <th class="w-2/12 px-4 py-2">Nombre</th>
                <th class="w-2/12 px-4 py-2">Correo Electrónico</th>
                <th class="w-2/12 px-4 py-2">Asunto</th>
                <th class="w-4/12 px-4 py-2">Mensaje</th>
                <th class="w-1/12 px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messages as $message)
                <tr>
                    <td class="border px-4 py-2 border-light">{{ $message->id }}</td>
                    <td class="border px-4 py-2 border-light">{{ $message->name }}</td>
                    <td class="border px-4 py-2 border-light">{{ $message->email }}</td>
                    <td class="border px-4 py-2 border-light">{{ $message->subject }}</td>
                    <td class="border px-4 py-2 border-light">{{ $message->message }}</td>
                    <td class="border px-4 py-2 border-light">
                        <x-button-secondary onclick="confirmDeletion({{ $message->id }})" class="py-2"
                            title="Eliminar">
                            <i class="fas fa-trash fa-fw"></i>
                        </x-button-secondary>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            function confirmDeletion(id) {
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
                            messageId: id
                        });
                    }
                })
            }
        </script>
    @endpush
</div>
