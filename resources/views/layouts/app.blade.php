<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}">

    <title>
        {{ (isset($title) ? $title . ' - ' : '') . config('app.name', 'Laravel') }}
    </title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-family antialiased bg-skin-background dark:bg-skin-background-dark text-slate-600 dark:text-slate-400"
    :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">
    <div id="preloader" class="fixed inset-0 bg-white flex justify-center items-center preloader-transition">
        <div class="flex justify-center items-center h-screen">
            <div class="rounded-full h-5 w-5 bg-gray-900 animate-ping"></div>
        </div>
    </div>
    <!-- Page wrapper -->
    <div class="flex h-screen overflow-hidden">

        <x-app.sidebar />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden" x-ref="contentarea">

            <x-app.header />

            <main class="">
                {{ $slot }}
            </main>
            <x-app.footer />
        </div>

    </div>

    <script>
        if (localStorage.getItem('sidebar-expanded') == 'true') {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }
    </script>

    <script src="{{ asset('js/crud_resources.js') }}"></script>

    @stack('scripts')
</body>

</html>
