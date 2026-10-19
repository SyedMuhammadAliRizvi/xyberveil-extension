<!doctype html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- This project is light-mode only — dark mode has been intentionally
         removed (no toggle, no localStorage/system-preference detection).
         The 'theme' store is kept as a harmless always-light shell only in
         case any leftover markup still references $store.theme.theme. --}}
    <script>
        // The sidebar collapse/expand choice is a user preference that must
        // survive normal (non-SPA) page navigation, so it's persisted here
        // instead of only living in Alpine's in-memory store.
        function resolveSidebarExpanded(fallback) {
            try {
                const saved = localStorage.getItem('sidebarExpanded');
                if (saved !== null) {
                    return saved === 'true';
                }
            } catch (e) {
                // localStorage unavailable (privacy mode, etc.) — fall through.
            }

            return fallback;
        }

        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', { theme: 'light' });

            Alpine.store('sidebar', {
                isExpanded: resolveSidebarExpanded(false),
                isMobileOpen: false,
                isHovered: false,
                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                    try {
                        localStorage.setItem('sidebarExpanded', String(this.isExpanded));
                    } catch (e) {
                        // Ignore — nothing to persist to.
                    }
                },
                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },
                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },
                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                },
            });
        });
    </script>

    <script src="{{ asset('assets/plugins/iconify/iconify-icon.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('assets/plugins/sweetalert/custom-sweetalert.css') }}" rel="stylesheet" />

    @yield('styles')
</head>

<body class="bg-[#eef0fb]" x-data="{ loaded: true }"
    x-init="$store.sidebar.isExpanded = resolveSidebarExpanded(false);
        window.addEventListener('resize', () => {
            if (window.innerWidth < 1280) {
                $store.sidebar.setMobileOpen(false);
                $store.sidebar.isExpanded = false;
            } else {
                $store.sidebar.isMobileOpen = false;
                $store.sidebar.isExpanded = resolveSidebarExpanded(false);
            }
        });">

    <div class="min-h-screen xl:flex" x-cloak>
        @include('layouts.tailadmin-backdrop')
        @include('layouts.tailadmin-sidebar')

        <div class="flex-1 transition-all duration-300 ease-in-out" :class="{
            'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
            'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        }">
            @include('layouts.tailadmin-header')

            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.js') }}"></script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>
