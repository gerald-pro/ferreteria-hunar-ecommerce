<header
    class="sticky top-0 bg-skin-navbar dark:bg-skin-navbar-dark border-b border-slate-200 dark:border-slate-700 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 -mb-px">

            <!-- Header: Left side -->
            <div class="flex">

                <!-- Hamburger button -->
                <button class="text-slate-500 hover:text-slate-600 lg:hidden" @click.stop="sidebarOpen = !sidebarOpen"
                    aria-controls="sidebar" :aria-expanded="sidebarOpen">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="5" width="16" height="2" />
                        <rect x="4" y="11" width="16" height="2" />
                        <rect x="4" y="17" width="16" height="2" />
                    </svg>
                </button>

            </div>

            <!-- Header: Right side -->
            <div class="flex items-center space-x-3">
                <!-- Search Button with Modal -->
                @can('searcher')
                    <x-modal-search />
                @endcan

                <!-- Info button -->
                <x-dropdown-themes align="right" />

                <!-- Dark mode toggle -->
                <x-theme-toggle />

                <!-- Divider -->
                <hr class="w-px h-6 bg-slate-200 dark:bg-slate-700 border-none" />
                @auth
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">

                                <div class="relative">
                                    <i class="fas fa-bell fa-fw"></i>
                                    @if (auth()->user()->unreadNotifications->count() >= 1)
                                        <span
                                            class="absolute top-0 right-1 inline-block w-4 h-4 text-xs bg-red-500 text-white rounded-full text-center transform translate-x-[-50%] translate-y-[-50%]">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </div>

                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if (auth()->user()->unreadNotifications->count() >= 1)
                                @foreach (auth()->user()->unreadNotifications as $item)
                                    <x-dropdown-link :href="route('administration.articles', ['article' => $item->data['id'], 'notification' => $item])">
                                        Nuevo articulo: {{ $item->data['title'] }} <br>
                                        Redactor: {{ $item->data['redactor'] ?? '' }}
                                    </x-dropdown-link>
                                @endforeach
                            @else
                                <span class="p-2">Sin notificaciones</span>
                            @endif
                        </x-slot>
                    </x-dropdown>

                    <!-- User button -->
                    <x-dropdown-profile align="right" />
                @endauth
                @guest
                    @if (Route::has('register'))
                        <x-link :href="route('login')" class="ml-auto text-slate-100">
                            {{ __('Log in') }}
                        </x-link>

                        <x-link :href="route('register')" class="ml-auto text-slate-100">
                            {{ __('Register') }}
                        </x-link>
                    @endif
                @endguest

               {{--  @can('article.create')
                    <a href="{{ route('articles.create') }}">
                        <x-button>
                            nueva publicación
                        </x-button>
                    </a>
                @endcan --}}
            </div>

        </div>
    </div>
</header>
