<div>
    <!-- Sidebar -->
    <div id="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 
        bg-skin-sidebar dark:bg-skin-sidebar-dark text-slate-900 dark:text-slate-100 p-4 transition-all duration-200 ease-in-out"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false" x-cloak="lg">

       

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2 z-10">
            <!-- Close button -->
            <button class="lg:hidden text-slate-500 hover:text-slate-400" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <a class="block" href="{{ route('home') }}">
                <div class="flex items-center justify-between">
                    <img src="{{ asset('images/hunar_ferreteria.png') }}" alt="Logo"
                        class="w-40 h-12 dark:filter dark:invert">
                    {{-- <span
                        class="font-bold ml-2 lg:sidebar-expanded:opacity-100 duration-200 lg:hidden lg:sidebar-expanded:block 2xl:block">
                        HUNAR
                    </span> --}}
                </div>
            </a>

        </div>

        <div class="absolute top-0 left-0 bg-theme-background bg-cover w-64 h-64 z-0 opacity-10 rotate-180"></div>

        <div class="absolute bottom-0 left-0 bg-theme-background bg-cover w-64 h-72 z-0 opacity-20"></div>


        <!-- Links -->
        <div class="space-y-8 z-10">
            <!-- Pages group -->
            <div>
                <ul class="mt-3">
                    @if ($unGrouped->count() > 0)
                        @foreach ($unGrouped as $sidebarItem)
                            <x-sidebar.sidebar-sublink
                                route="{{ $sidebarItem->page->route == '/' ? $sidebarItem->page->route : trim($sidebarItem->page->route, '/') }}"
                                icon="{{ $sidebarItem->icon }}">{{ $sidebarItem->name }}</x-sidebar.sidebar-sublink>
                        @endforeach
                    @endif

                    @foreach ($groups as $group)
                        @if ($group->items->count() > 0)
                            <x-sidebar.sidebar-headlink text="{{ $group->name }}" :items="$group->items"
                                icon="{{ $group->icon }}">
                                @foreach ($group->items->sortBy('id') as $sidebarItem)
                                    <x-sidebar.sidebar-sublink icon="{{ $sidebarItem->icon }}"
                                        route="{{ $sidebarItem->page->route == '/' ? $sidebarItem->page->route : trim($sidebarItem->page->route, '/') }}">{{ $sidebarItem->name }}</x-sidebar-sublink>
                                @endforeach
                            </x-sidebar.sidebar-headlink>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>


        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto z-10">
            <div class="px-3 py-2">
                <button @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
                        <path class=""
                            d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z" />
                        <path class="" d="M3 23H1V1h2z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
