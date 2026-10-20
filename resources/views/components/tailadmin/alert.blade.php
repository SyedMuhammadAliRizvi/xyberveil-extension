@props([
    'type' => 'info', {{-- info | success | warning | danger --}}
    'message' => '',
])

@php
    $styles = [
        'info' => 'bg-blue-light-50 text-blue-light-700 border-blue-light-200 dark:bg-blue-light-500/10 dark:text-blue-light-400 dark:border-blue-light-500/20',
        'success' => 'bg-success-50 text-success-700 border-success-200 dark:bg-success-500/10 dark:text-success-400 dark:border-success-500/20',
        'warning' => 'bg-warning-50 text-warning-700 border-warning-200 dark:bg-warning-500/10 dark:text-warning-400 dark:border-warning-500/20',
        'danger' => 'bg-error-50 text-error-700 border-error-200 dark:bg-error-500/10 dark:text-error-400 dark:border-error-500/20',
    ];
@endphp

<div x-data="{ show: true }" x-show="show"
    class="mb-5 flex items-start justify-between gap-3 rounded-xl border px-4 py-3 text-theme-sm {{ $styles[$type] ?? $styles['info'] }}">
    <span>{{ $message }}</span>
    <button type="button" @click="show = false" class="shrink-0 opacity-70 hover:opacity-100">
        <iconify-icon icon="bx:x" width="18" height="18"></iconify-icon>
    </button>
</div>
