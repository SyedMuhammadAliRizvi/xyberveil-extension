@section('title', 'Online Users Monitor')
<div wire:poll.120000ms="pollRefresh">

    <x-tailadmin.breadcrumb :items="[['label' => 'Online Users Monitor']]" />

    @php $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'; @endphp

    @php
        $proxyStats = $proxySummary['stats'] ?? null;
        $proxySessions = $proxySummary['sessions'] ?? [];
    @endphp

    <div class="mb-5 grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
            <x-tailadmin.metric-card icon="mdi:account-multiple" icon-class="bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400"
                label="Total Proxy Users" :value="number_format($proxyStats['users']['total'] ?? 0)" />
            <x-tailadmin.metric-card icon="mdi:account-check" icon-class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400"
                label="Active" :value="number_format($proxyStats['users']['active'] ?? 0)" />
            <x-tailadmin.metric-card icon="mdi:account-clock" icon-class="bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400"
                label="Pending Approval" :value="number_format($proxyStats['users']['pending'] ?? 0)" />
            <x-tailadmin.metric-card icon="mdi:lan-connect" icon-class="bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400"
                label="Live Sessions" :value="number_format($proxyStats['sessions']['live'] ?? 0)" />
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Live Proxy Sessions</h3>
                    @if (! empty($proxySummary['fetched_at']))
                        <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Last updated: {{ \Carbon\Carbon::parse($proxySummary['fetched_at'])->diffForHumans() }}</p>
                    @else
                        <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">No data yet. Refresh to fetch.</p>
                    @endif
                    @if (! empty($proxySummary['error']))
                        <div class="mt-0.5 flex items-center gap-1 text-theme-xs text-error-500">
                            <iconify-icon icon="mdi:alert-circle" width="12" height="12"></iconify-icon>
                            {{ $proxySummary['error'] }}
                        </div>
                    @endif
                </div>
                <button type="button" wire:click="refreshProxy" wire:loading.attr="disabled" wire:target="refreshProxy"
                    class="flex h-10 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-60 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                    <iconify-icon icon="mdi:refresh" width="16" height="16" wire:loading.class="hidden" wire:target="refreshProxy"></iconify-icon>
                    <iconify-icon icon="mdi:loading" width="16" height="16" class="hidden animate-spin" wire:loading.class.remove="hidden" wire:target="refreshProxy"></iconify-icon>
                    Refresh
                </button>
            </div>

            <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-3 font-medium">User</th>
                            <th class="px-3 py-3 font-medium">Server</th>
                            <th class="px-3 py-3 font-medium">Client IP</th>
                            <th class="px-3 py-3 font-medium">Started</th>
                            <th class="px-5 py-3 font-medium">Duration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($proxySessions as $session)
                            <tr wire:key="proxy-session-{{ $loop->index }}-{{ $session['started_at'] ?? '' }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/[0.05]">
                                <td class="px-5 py-3">
                                    <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $session['user_display'] ?? '—' }}</div>
                                    <code class="text-theme-xs text-gray-400">{{ $session['user_email'] ?? '—' }}</code>
                                </td>
                                <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $session['server_flag'] ?? '' }} {{ $session['server_name'] ?? '—' }}</td>
                                <td class="px-3 py-3"><code class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $session['client_ip'] ?? '—' }}</code></td>
                                <td class="px-3 py-3 text-theme-xs text-gray-400">
                                    {{ ! empty($session['started_at']) ? \Carbon\Carbon::parse($session['started_at'])->diffForHumans() : '—' }}
                                </td>
                                <td class="px-5 py-3 text-theme-xs text-gray-400">
                                    @php $d = $session['duration_seconds'] ?? 0; @endphp
                                    {{ sprintf('%02d:%02d:%02d', intdiv($d, 3600), intdiv($d % 3600, 60), $d % 60) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">
                                    <iconify-icon icon="mdi:lan-disconnect" width="32" height="32" class="mx-auto mb-2 block opacity-50"></iconify-icon>
                                    No live proxy sessions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</div>

@script
<script>
    $wire.on('sweetAlert', (event) => {
        Swal.fire({ title: event.title, text: event.message, icon: event.type, timer: 3000, showConfirmButton: false });
    });
</script>
@endscript
