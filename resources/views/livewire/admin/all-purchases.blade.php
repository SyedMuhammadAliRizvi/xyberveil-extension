@section('title', 'All Transactions')
<div>
    @if (session('message'))
        <x-tailadmin.alert type="info" :message="session('message')" />
    @endif

    <x-tailadmin.breadcrumb :items="[['label' => 'Transactions']]" />

    @php $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'; @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Transactions</h3>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <select wire:model.live="perPage"
                class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

            <div class="flex items-center gap-2">
                <label class="relative block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                    </span>
                    <input type="text" wire:model.live.500ms="search" placeholder="Search..."
                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-56">
                </label>

                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" @click="open = !open"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                        <iconify-icon icon="line-md:filter" width="20" height="20"></iconify-icon>
                    </button>
                    <div x-show="open" x-cloak x-transition @click.stop
                        class="absolute right-0 z-50 mt-2 w-64 rounded-xl border border-gray-200 bg-white p-4 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-3 flex items-center justify-between">
                            <h6 class="text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Filters</h6>
                            <button type="button" wire:click="resetFilters" class="text-theme-xs font-medium text-error-500 hover:underline">Reset</button>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Max Amount</label>
                                <select wire:model.live="amountFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Max Amount</option>
                                    <option value="10">Under $10</option>
                                    <option value="20">Under $20</option>
                                    <option value="50">Under $50</option>
                                    <option value="100">Under $100</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Status</label>
                                <select wire:model.live="statusFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Status</option>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-3 py-3 font-medium">User</th>
                        <th class="px-3 py-3 font-medium">Plan</th>
                        <th class="px-3 py-3 font-medium">Amount Paid</th>
                        <th class="px-3 py-3 font-medium">Start Date</th>
                        <th class="px-3 py-3 font-medium">End Date</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Provider</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($purchases as $purchase)
                        <tr wire:key="purchase-{{ $purchase->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $purchase->id }}</td>
                            <td class="px-3 py-3">
                                <a href="{{ route('user.manage', $purchase->user->slug) }}" class="text-theme-sm font-medium text-blue-light-500 hover:underline">
                                    {{ Str::title($purchase->user->name) ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $purchase->plan->name ?? 'N/A' }}</td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">${{ number_format($purchase->amount_paid, 2) }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $purchase->starts_at->toFormattedDateString() }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ optional($purchase->ends_at)->toFormattedDateString() ?? 'N/A' }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-medium text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ ucfirst($purchase->provider) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-sm text-gray-400">No purchases found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $purchases->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
@script
    <script>
        $wire.on('sweetAlert', (event) => {
            Swal.fire({
                title: event.title,
                text: event.message,
                icon: event.type,
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endscript
