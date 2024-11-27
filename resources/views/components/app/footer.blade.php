<!-- You will probably want to customize this component. -->

@props([
    'showCopyright' => true,
    'copyrightStart' => 2024,

    'showCredit' => true,

    'showVersion' => true,
])

<footer class="w-full mt-auto p-5 pt-6 bg-opacity-10 text-gray-800 dark:text-gray-200 text-center">

    @isset($currentPage)
        <p class="mt-3">
            Página: {{ Request::getRequestUri() }} - Visitas {{ $currentPage->visits }}
        </p>
    @endisset

    @if ($showCopyright)
        <div class="mx-3">
            <small class="text-sm">
                Copyright &copy;
                {{ $copyrightStart != date('Y') ? $copyrightStart . '-' . date('Y') : $copyrightStart }}
               {{--  Gerald Avalos --}}
               Tecnología Web
            </small>
        </div>
    @endif

</footer>
