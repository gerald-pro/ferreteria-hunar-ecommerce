@props(['size' => '3', 'class' => ''])

<div {{ $attributes->merge(['class' => "inline-block {$class} h-{$size} w-{$size} my-auto animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite]"]) }}
    role="status">
</div>
