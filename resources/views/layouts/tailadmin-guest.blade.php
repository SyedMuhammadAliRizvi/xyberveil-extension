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

    {{-- Light-mode only — see layouts/tailadmin.blade.php for details. --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', { theme: 'light' });
        });
    </script>

    <script src="{{ asset('assets/plugins/iconify/iconify-icon.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('assets/plugins/sweetalert/custom-sweetalert.css') }}" rel="stylesheet" />

    @yield('styles')
</head>

<body class="bg-[#eef0fb]">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">

        <div class="mb-6 flex flex-col items-center">
            <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="mb-3 h-16 w-16">
            <h1 class="text-2xl font-bold tracking-tight">
                <span class="text-brand-500">XyberVeil VPN</span>
                <span class="text-slate-900">Extension</span>
            </h1>
        </div>

        <div class="w-full max-w-md rounded-xl border border-slate-100 bg-white p-8 shadow-sm">
            @yield('content')
        </div>

    </div>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.js') }}"></script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>
