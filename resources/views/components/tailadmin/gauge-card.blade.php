@props([
    'title',
    'subtitle' => '',
    'chartId',
    'note' => '',
    'zoomable' => false,
])

{{-- Big semi-circle radial gauge card (pattern from TailAdmin's "Monthly Target" widget).
     The actual ApexCharts radialBar instance is created in the page's script section
     targeting #{{ $chartId }} — this component only provides the markup shell. --}}
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-5 transition-colors duration-200 hover:border-accent dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-accent sm:p-6']) }}>
    <div class="flex items-start justify-between">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h3>
            @if ($subtitle)
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>
        @if ($zoomable)
            <x-tailadmin.expand-button :chart-id="$chartId" :title="$title" />
        @endif
    </div>

    <div id="{{ $chartId }}" class="mx-auto -mt-2" style="max-width:280px; min-height:220px"></div>

    {{-- Wrapped with a matching "{chartId}-extra" id so the zoom modal can clone this
         summary alongside the enlarged chart (see zoomChart() in the page script). --}}
    <div id="{{ $chartId }}-extra">
        @if ($note)
            <p class="mx-auto -mt-4 max-w-xs text-center text-theme-sm text-gray-500 dark:text-gray-400">{{ $note }}</p>
        @endif

        @isset($footer)
            <div class="mt-4 flex items-center justify-center gap-5 rounded-xl border-t border-gray-100 pt-4 dark:border-gray-800">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
