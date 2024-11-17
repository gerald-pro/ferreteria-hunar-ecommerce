<div>
    <form wire:submit.prevent="submit" class="w-full max-w-lg mx-auto p-6">
        <div class="mb-4">
            <x-label for="name" value="Nombre" />
            <x-input class="block w-full"  id="name" type="text" wire:model="name" placeholder="Nombre" required class="w-full" />
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <x-label for="email" value="Correo Electrónico" />
            <x-input class="block w-full"  id="email" type="email" wire:model="email" placeholder="Correo Electrónico" required />
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <x-label for="subject" value="Asunto" />
            <x-input class="block w-full" id="subject" type="text" wire:model="subject" placeholder="Asunto" required/>
            @error('subject') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <x-label for="message" value="Mensaje" />
            <x-textarea id="message" wire:model="message" placeholder="Escribe tu mensaje" required/>
            @error('message') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="flex justify-end">
            <x-button type="submit">Enviar</x-button>
        </div>
    </form>
</div>