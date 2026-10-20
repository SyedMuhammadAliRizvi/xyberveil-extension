@props([
    'icon',
    'iconClass' => 'bg-gray-100 text-gray-800 dark:bg-white/5 dark:text-white/90',
    'label',
    'value',
    'valueId' => null,
    'trend' => null, {{-- ['direction' => 'up'|'down', 'text' => '11.01%'] --}}
])

{{--
    Premium KPI card: icon in a large rounded square, label + big value below,
    optional trend pill inline on the right (pattern from the TailAdmin ecommerce dashboard).
    `iconClass` must be a literal Tailwind class string at the call site (not interpolated)
    so the build-time class scanner can find it.
--}}
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-5 transition-colors duration-200 hover:border-accent dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-accent md:p-6']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $iconClass }}">
        <iconify-icon icon="{{ $icon }}" width="26" height="26"></iconify-icon>
    </div>

    <div class="mt-5 flex items-end justify-between">
        <div>
            <p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
            <h4 @if ($valueId) id="{{ $valueId }}" @endif class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ $value }}
            </h4>
        </div>

        @if ($trend)
            @if (is_array($trend))
                <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-theme-sm font-medium {{ $trend['direction'] === 'up' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' }}">
                    <iconify-icon icon="{{ $trend['direction'] === 'up' ? 'bxs:up-arrow' : 'bxs:down-arrow' }}" width="10" height="10"></iconify-icon>
                    {{ $trend['text'] }}
                </span>
            @else
                {{-- Custom markup passed via <x-slot:trend> (e.g. a "Live" badge). --}}
                {{ $trend }}
            @endif
        @endif
    </div>

    {{ $slot ?? '' }}
</div>
