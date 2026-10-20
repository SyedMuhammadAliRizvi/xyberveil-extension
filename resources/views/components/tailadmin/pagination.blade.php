@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav class="flex flex-col items-center justify-between gap-3 sm:flex-row">
            <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                {{ __('Showing') }}
                <span class="font-medium text-gray-800 dark:text-white/90">{{ $paginator->firstItem() }}</span>
                {{ __('to') }}
                <span class="font-medium text-gray-800 dark:text-white/90">{{ $paginator->lastItem() }}</span>
                {{ __('of') }}
                <span class="font-medium text-gray-800 dark:text-white/90">{{ $paginator->total() }}</span>
                {{ __('results') }}
            </p>

            <ul class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-300 dark:text-gray-700">
                            <iconify-icon icon="bx:chevron-left" width="18" height="18"></iconify-icon>
                        </span>
                    </li>
                @else
                    <li>
                        <button type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                            <iconify-icon icon="bx:chevron-left" width="18" height="18"></iconify-icon>
                        </button>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li>
                            <span class="flex h-9 w-9 items-center justify-center text-theme-sm text-gray-400">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">
                                    <span aria-current="page"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-500 text-theme-sm font-medium text-white">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">
                                    <button type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                                        {{ $page }}
                                    </button>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <button type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                            <iconify-icon icon="bx:chevron-right" width="18" height="18"></iconify-icon>
                        </button>
                    </li>
                @else
                    <li>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-300 dark:text-gray-700">
                            <iconify-icon icon="bx:chevron-right" width="18" height="18"></iconify-icon>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>
