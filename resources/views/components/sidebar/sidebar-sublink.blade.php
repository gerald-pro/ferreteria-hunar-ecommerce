@props(['route', 'icon' => null])

<li class="@if ($icon) px-3 py-2 rounded-sm mb-0.5 last:mb-0 @else mb-1 last:mb-0 @endif">
    <a class="block transition duration-150 truncate @if (request()->is($route))) {{ '!text-primary-hover' }} @endif hover:text-primary"
        href="{{ url($route) }}">
        @if ($icon)
            <div class="flex items-center">
                <i class="fa-solid fa-lg {{$icon}} fa-fw"></i>
                <span
                    class="font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ $slot }}</span>
            </div>
        @else
            <span class="font-medium duration-200">{{ $slot }}</span>
        @endif
    </a>
</li>
