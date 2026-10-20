{{-- 'name' is a unique event-name suffix (e.g. 'vps-create') used to open/close this modal via events. --}}
@props([
    'name',
    'maxWidth' => 'max-w-[600px]',
])

{{--
    Reusable Alpine-driven modal shell (adapted from the TailAdmin reference's ui/modal component).
    State is local to this component (x-data, not a global Alpine.store) to avoid any cross-modal
    state coupling; open/close is triggered via plain window CustomEvents so both a button click
    (`@click="$dispatch('open-modal-{name}')"`) and a Livewire `$wire.on(...)` handler
    (`window.dispatchEvent(new CustomEvent('close-modal-{name}'))`) can control it from anywhere
    on the page without needing to share Alpine store state.
--}}
<div x-data="{ open: false }"
    @open-modal-{{ $name }}.window="open = true"
    @close-modal-{{ $name }}.window="open = false"
    x-show="open" x-cloak @keydown.escape.window="open = false"
    class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">

    <div @click="open = false" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[8px] dark:bg-gray-900/60"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div @click.stop
        class="relative w-full {{ $maxWidth }} max-h-[90vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-theme-xl dark:bg-gray-900"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

        <button type="button" @click="open = false"
            class="absolute right-5 top-5 flex h-9 w-9 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/5 dark:hover:text-gray-300">
            <iconify-icon icon="bx:x" width="20" height="20"></iconify-icon>
        </button>

        {{ $slot }}
    </div>
</div>
