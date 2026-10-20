@props([
    'items', {{-- array of ['label' => string, 'route' => string|null, 'params' => array|null] — last item renders as plain text --}}
])

<nav class="mb-5 flex flex-wrap items-center gap-2 text-theme-sm">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400">
        <iconify-icon icon="bx:home-alt" width="16" height="16"></iconify-icon>
        Home
    </a>
    @foreach ($items as $item)
        <iconify-icon icon="bx:chevron-right" width="14" height="14" class="text-gray-400"></iconify-icon>
        @if (! $loop->last && ($item['route'] ?? null))
            <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400">{{ $item['label'] }}</a>
        @else
            <span class="font-medium text-gray-800 dark:text-white/90">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
