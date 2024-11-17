@props(['text' => '', 'icon' => 'fa-question', 'items' => []])

@php
    $isActiveGroup = false;
    foreach ($items as $item) {
        if (request()->is(trim($item->page->route, '/'))) {
            $isActiveGroup = true;
            break;
        }
    }
@endphp

<li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if ($isActiveGroup) {{ 'bg-skin-background dark:bg-skin-navbar-dark' }} @endif"
    x-data="{ open: {{ $isActiveGroup ? 1 : 0 }} }">
    <a class="block truncate transition duration-150 @if ($isActiveGroup) {{ 'text-primary-hover' }} @endif"
        href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas {{ $icon }} fa-lg fa-fw"></i>
                <span
                    class="font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ $text }}</span>
            </div>
            <!-- Icon -->
            <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                <i class="fa fa-sm fa-chevron-down fa-fw @if ($isActiveGroup) {{ 'fa-rotate-180' }} @endif"
                    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12"></i>
            </div>
        </div>
    </a>
    <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
        <ul class="pl-9 mt-1 @if (!$isActiveGroup) {{ 'hidden' }} @endif"
            :class="open ? '!block' : 'hidden'">
            {{ $slot }}
        </ul>
    </div>
</li>
