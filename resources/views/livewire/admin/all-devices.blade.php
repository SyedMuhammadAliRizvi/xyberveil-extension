@section('title', 'All Devices')
<div x-data>
    <x-tailadmin.breadcrumb :items="[['label' => 'Devices']]" />

    @php $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'; @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Devices</h3>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <select wire:model.live="perPage"
                class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

            <div class="flex items-center gap-2">
                <label class="relative block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                    </span>
                    <input type="text" wire:model.live.500ms="search" placeholder="Search by name, model, IP, user..."
                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-72">
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
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Platform</label>
                                <select wire:model.live="platformFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="">All Platforms</option>
                                    <option value="android">Android</option>
                                    <option value="ios">iOS</option>
                                    <option value="macos">macOS</option>
                                    <option value="windows">Windows</option>
                                    <option value="web">Web</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Device Type</label>
                                <select wire:model.live="deviceTypeFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="">All Types</option>
                                    <option value="mobile">Mobile</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="desktop">Desktop</option>
                                    <option value="laptop">Laptop</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Status</label>
                                <select wire:model.live="statusFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="blocked">Blocked</option>
                                    <option value="revoked">Revoked</option>
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
                        <th class="px-3 py-3 font-medium">Device</th>
                        <th class="px-3 py-3 font-medium">Platform / OS</th>
                        <th class="px-3 py-3 font-medium">IP Address</th>
                        <th class="px-3 py-3 font-medium">Location</th>
                        <th class="px-3 py-3 font-medium">Last Seen</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($devices as $device)
                        <tr wire:key="device-{{ $device->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $device->id }}</td>
                            <td class="px-3 py-3">
                                <a href="{{ route('user.manage', $device->user->slug) }}" class="text-theme-sm font-medium text-blue-light-500 hover:underline">
                                    {{ $device->user->name }}
                                </a>
                                <div class="text-theme-xs text-gray-400">{{ $device->user->email }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-2">
                                    <iconify-icon icon="{{ match ($device->platform) {
                                        'android' => 'mdi:android',
                                        'ios' => 'mdi:apple-ios',
                                        'macos' => 'mdi:apple',
                                        'windows' => 'mdi:microsoft-windows',
                                        'web' => 'mdi:web',
                                        default => 'mdi:devices',
                                    } }}" width="20" height="20" class="shrink-0 text-gray-400"></iconify-icon>
                                    <div>
                                        <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $device->name ?: 'Unnamed Device' }}</div>
                                        <div class="text-theme-xs text-gray-400">{{ $device->model ?: '—' }}</div>
                                        @if ($device->browser)
                                            <div class="text-theme-xs text-gray-400">{{ $device->browser }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                <div>{{ Str::title($device->platform ?? '—') }}</div>
                                <div class="text-theme-xs text-gray-400">{{ $device->os ?: '—' }}</div>
                                @if ($device->device_type)
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/5 dark:text-gray-400">{{ Str::title($device->device_type) }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm">
                                @php $ipBlocked = $device->ip_address && isset($blockedIpSet[$device->ip_address]); @endphp
                                <code class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $device->ip_address ?: '—' }}</code>
                                @if ($ipBlocked)
                                    <span class="ml-1 rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-400">IP Blocked</span>
                                @endif
                                @if ($device->app_version)
                                    <div class="text-theme-xs text-gray-400">v{{ $device->app_version }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                @if ($device->city || $device->country)
                                    {{ collect([$device->city, $device->country])->filter()->implode(', ') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $device->last_seen_at ? $device->last_seen_at->diffForHumans() : 'Never' }}</td>
                            <td class="px-3 py-3 text-center">
                                @if ($device->is_blocked)
                                    <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-semibold text-error-600 dark:bg-error-500/15 dark:text-error-400">Blocked</span>
                                @elseif ($device->isRevoked())
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Revoked</span>
                                @else
                                    <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Active</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    @if ($device->is_blocked)
                                        <button type="button" wire:click="unblockDevice({{ $device->id }})" title="Unblock Device"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                            <iconify-icon icon="mdi:lock-open-variant-outline" width="18" height="18"></iconify-icon>
                                        </button>
                                    @else
                                        <button type="button" wire:click="$js.confirmBlockDevice({{ $device->id }})" title="Block Device"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                            <iconify-icon icon="mdi:block-helper" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif

                                    @if (! $device->isRevoked())
                                        <button type="button" wire:click="$js.confirmRevokeDevice({{ $device->id }})" title="Revoke Access"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">
                                            <iconify-icon icon="mdi:key-remove" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif

                                    @if ($device->ip_address)
                                        @if ($ipBlocked)
                                            <button type="button" wire:click="$js.confirmUnblockIp('{{ $device->ip_address }}')" title="Unblock IP"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                                <iconify-icon icon="mdi:ip-network-outline" width="18" height="18"></iconify-icon>
                                            </button>
                                        @else
                                            <button type="button" wire:click="$js.confirmBlockIp({{ $device->id }}, '{{ $device->ip_address }}')" title="Block IP"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                                <iconify-icon icon="mdi:ip-network-outline" width="18" height="18"></iconify-icon>
                                            </button>
                                        @endif
                                    @endif

                                    <button type="button" wire:click="$js.confirmRemoveDevice({{ $device->id }})" title="Remove Device"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-gray-400">No devices found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $devices->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
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

        $js('confirmBlockDevice', (id) => {
            Swal.fire({
                title: 'Block Device?',
                text: 'This will immediately revoke the session and block the device.',
                icon: 'warning',
                input: 'text',
                inputLabel: 'Reason (optional)',
                inputPlaceholder: 'Enter reason for blocking',
                showCancelButton: true,
                confirmButtonColor: '#f0ad4e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, block it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.blockDevice(id, result.value || '');
                }
            });
        });

        $js('confirmRevokeDevice', (id) => {
            Swal.fire({
                title: 'Revoke Access?',
                text: 'The device session will be terminated immediately.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, revoke!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.revokeDevice(id);
                }
            });
        });

        $js('confirmBlockIp', (id, ip) => {
            Swal.fire({
                title: `Block IP: ${ip}?`,
                text: 'This IP will be blocked from making any API requests.',
                icon: 'warning',
                input: 'text',
                inputLabel: 'Reason (optional)',
                inputPlaceholder: 'Enter reason for blocking',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, block IP!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.blockDeviceIp(id, result.value || '');
                }
            });
        });

        $js('confirmUnblockIp', (ip) => {
            Swal.fire({
                title: `Unblock IP: ${ip}?`,
                text: 'This IP will be allowed to make API requests again.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, unblock!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.unblockDeviceIp(ip);
                }
            });
        });

        $js('confirmRemoveDevice', (id) => {
            Swal.fire({
                title: 'Remove Device?',
                text: 'The device record will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.removeDevice(id);
                }
            });
        });
    </script>
@endscript
