@props(['id' => null, 'maxWidth' => null])

<x-modal name="confirm-user-deletion"  :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg">
            {{ $title }}
        </div>

        <div class="mt-4">
            {{ $content }}
        </div>
    </div>

    <div class="px-6 py-4 bg-skin-background text-right">
        {{ $footer }}
    </div>
</x-modal>
