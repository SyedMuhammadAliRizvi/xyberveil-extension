@props([
    'wireModel',
    'label',
])

<label {{ $attributes->merge(['class' => 'flex items-center gap-3 cursor-pointer']) }}>
    <span class="relative inline-flex h-6 w-11 shrink-0 items-center">
        <input type="checkbox" @if ($wireModel) wire:model="{{ $wireModel }}" @endif class="peer sr-only">
        <span class="absolute inset-0 rounded-full bg-gray-200 transition-colors peer-checked:bg-brand-500 dark:bg-white/10"></span>
        <span class="absolute left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
    </span>
    <span class="text-theme-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
</label>
