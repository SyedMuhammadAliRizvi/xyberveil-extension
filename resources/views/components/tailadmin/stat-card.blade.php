@props([
    'icon',
    'iconClass' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400',
    'value',
    'label',
    'valueId' => null,
    'chartId' => null,
])

{{--
    Reusable KPI tile: icon badge + optional mini-chart slot on top, big value, small label below.
    `iconClass` must be a literal, complete Tailwind class string at the call site (not built via
    string interpolation) so Tailwind's build-time scanner can pick it up.
--}}
<div {{ $attributes->merge(['class' => '']) }}>
    <div class="flex items-center justify-between">
        <div class="flex items-center justify-center w-10 h-10 rounded-xl {{ $iconClass }}">
            <iconify-icon icon="{{ $icon }}" width="18" height="18"></iconify-icon>
        </div>
        @if ($chartId)
            <div id="{{ $chartId }}" class="w-14 h-8"></div>
        @endif
    </div>
    {{-- $value is trusted markup from our own Blade callers (not user input), so it's intentionally unescaped
         to allow composite values like "12<span>/20</span>". --}}
    <h4 @if ($valueId) id="{{ $valueId }}" @endif class="mt-3 text-lg font-bold text-gray-800 dark:text-white/90">
        {!! $value !!}
    </h4>
    <p class="text-theme-xs uppercase tracking-wide font-medium text-gray-500 dark:text-gray-400">
        {{ $label }}
    </p>
</div>
