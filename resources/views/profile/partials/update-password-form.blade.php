<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Actualizar contraseña
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Asegúrese de que su cuenta utilice una contraseña larga y aleatoria para mantenerse segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <x-label for="current_password" value="Contraseña actual" />
            <x-input id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                autocomplete="current-password" />
            @error('current_password')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <x-label for="password" value="Nueva contraseña" />
            <x-input id="password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" />
            @error('password')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <x-label for="password_confirmation" value="Confirmar contraseña" />
            <x-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full"
                autocomplete="new-password" />
            @error('password_confirmation')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <x-button>Guardar</x-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">Guardado</p>
            @endif
        </div>
    </form>
</section>
