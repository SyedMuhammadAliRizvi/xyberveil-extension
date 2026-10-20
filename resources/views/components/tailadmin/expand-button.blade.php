@props([
    'chartId',
    'title',
    'type' => 'apex', {{-- 'apex' (ApexCharts instance) or 'map' (jsVectorMap) --}}
])

<button type="button" onclick="zoomChart('{{ $chartId }}', @js($title), '{{ $type }}')"
    class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/5 dark:hover:text-gray-300"
    title="Expand">
    <iconify-icon icon="bx:expand" width="16" height="16"></iconify-icon>
</button>
