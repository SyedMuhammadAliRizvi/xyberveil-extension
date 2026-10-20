@section('title', 'All Servers')
<div>
    @if (session('message'))
        <x-tailadmin.alert type="info" :message="session('message')" />
    @endif

    <x-tailadmin.breadcrumb :items="[['label' => 'VPN Servers']]" />

    @php $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'; @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Servers</h3>
            <a href="{{ route('servers.add') }}"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                Create Server
            </a>
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
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Status</label>
                                <select wire:model.live="statusFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Type</label>
                                <select wire:model.live="typeFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Type</option>
                                    <option value="free">Free</option>
                                    <option value="premium">Premium</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Platform</label>
                                <select wire:model.live="platformFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Platform</option>
                                    <option value="windows">Windows</option>
                                    <option value="macos">Mac</option>
                                    <option value="ios">iOS</option>
                                    <option value="android">Android</option>
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
                        <th class="px-3 py-3 font-medium">Image</th>
                        <th class="px-3 py-3 font-medium">Name</th>
                        <th class="px-3 py-3 font-medium">Platforms</th>
                        <th class="px-3 py-3 text-center font-medium">Type</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($servers as $server)
                        <tr wire:key="vpn-server-{{ $server->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/[0.05]">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $server->id }}</td>
                            <td class="px-3 py-3">
                                @if ($server->getFirstMediaUrl('image'))
                                    <img src="{{ $server->getFirstMediaUrl('image') }}" class="h-10 w-10 rounded-lg object-cover">
                                @elseif ($server->flag_url)
                                    <img src="{{ $server->flag_url }}" class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <span class="text-theme-xs text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $server->name }}</td>
                            <td class="px-3 py-3">
                                @php
                                    $platformColors = [
                                        'windows' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                        'android' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400',
                                        'ios' => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                                        'macos' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                    ];
                                @endphp
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($platformColors as $platform => $classes)
                                        @if ($server->$platform)
                                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $classes }}">{{ ucfirst($platform) }}</span>
                                        @endif
                                    @endforeach
                                    @if (! $server->android && ! $server->ios && ! $server->macos && ! $server->windows)
                                        <span class="text-theme-xs text-warning-600 dark:text-warning-400">No platforms selected</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $server->isPremium() ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400' : 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' }}">
                                    {{ ucfirst($server->type) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $server->isActive() ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' }}">
                                    {{ $server->isActive() ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('all.sub-servers', $server->id) }}" title="Sub-Servers"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                        <iconify-icon icon="solar:server-square-broken" width="18" height="18"></iconify-icon>
                                    </a>
                                    <a href="{{ route('servers.edit', $server->id) }}" title="Edit"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                        <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                    </a>
                                    <button type="button" wire:click="$js.confirmDelete({{ $server->id }})" title="Delete"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">No Servers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $servers->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
@script
    <script>
        $js('confirmDelete', (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteServer(id);
                }
            });
        });

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
