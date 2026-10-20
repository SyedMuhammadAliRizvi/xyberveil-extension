@section('title', 'Blocked IPs')
<div x-data>
    <x-tailadmin.breadcrumb :items="[['label' => 'Blocked IPs']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Blocked IPs</h3>
            <button type="button" @click="$dispatch('open-modal-blocked-ip-add')"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="mdi:ip-network-outline" width="18" height="18"></iconify-icon>
                Block IP
            </button>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <select wire:model.live="perPage"
                class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <label class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                </span>
                <input type="text" wire:model.live.500ms="search" placeholder="Search by IP or reason..."
                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-72">
            </label>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-3 py-3 font-medium">IP Address</th>
                        <th class="px-3 py-3 font-medium">Reason</th>
                        <th class="px-3 py-3 font-medium">Blocked By</th>
                        <th class="px-3 py-3 font-medium">Expires At</th>
                        <th class="px-3 py-3 font-medium">Blocked At</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($blockedIps as $blocked)
                        <tr wire:key="blocked-ip-{{ $blocked->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $blocked->id }}</td>
                            <td class="px-3 py-3"><code class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $blocked->ip_address }}</code></td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $blocked->reason ?: '—' }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $blocked->blockedBy?->name ?? 'System' }}</td>
                            <td class="px-3 py-3 text-theme-sm">
                                @if ($blocked->expires_at)
                                    @if ($blocked->isExpired())
                                        <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">Expired {{ $blocked->expires_at->diffForHumans() }}</span>
                                    @else
                                        <div class="text-gray-600 dark:text-gray-400">{{ $blocked->expires_at->toFormattedDateString() }}</div>
                                        <div class="text-theme-xs text-gray-400">{{ $blocked->expires_at->diffForHumans() }}</div>
                                    @endif
                                @else
                                    <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-400">Permanent</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                <div>{{ $blocked->created_at->toFormattedDateString() }}</div>
                                <div class="text-theme-xs text-gray-400">{{ $blocked->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <button type="button" wire:click="$js.confirmUnblock({{ $blocked->id }}, '{{ $blocked->ip_address }}')"
                                    class="flex h-9 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-theme-xs font-medium text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                    <iconify-icon icon="mdi:lock-open-variant-outline" width="16" height="16"></iconify-icon>
                                    Unblock
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">No blocked IPs</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $blockedIps->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Block IP modal --}}
    <x-tailadmin.modal name="blocked-ip-add">
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Block IP Address</h4>

        <form wire:submit.prevent="addIp" class="space-y-4">
            <div>
                <label class="{{ $labelClass }}">IP Address <span class="text-error-500">*</span></label>
                <input type="text" placeholder="e.g. 154.12.34.56" wire:model.defer="newIp" class="{{ $inputClass }}">
                @error('newIp') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Reason <span class="text-gray-400">(optional)</span></label>
                <input type="text" placeholder="Reason for blocking" wire:model.defer="newReason" class="{{ $inputClass }}">
                @error('newReason') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Expires At <span class="text-gray-400">(optional — leave blank for permanent)</span></label>
                <input type="datetime-local" wire:model.defer="newExpiresAt" class="{{ $inputClass }}">
                @error('newExpiresAt') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" @click="$dispatch('close-modal-blocked-ip-add')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Cancel
            </button>
            <button type="button" wire:click="addIp"
                class="rounded-lg bg-error-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-error-600">
                Block IP
            </button>
        </div>
    </x-tailadmin.modal>
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

        $wire.on('closeModal', () => {
            window.dispatchEvent(new CustomEvent('close-modal-blocked-ip-add'));
        });

        $js('confirmUnblock', (id, ip) => {
            Swal.fire({
                title: `Unblock ${ip}?`,
                text: 'This IP will be allowed to make API requests again.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, unblock!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.unblock(id);
                }
            });
        });
    </script>
@endscript
