{{-- Provider bandwidth quota (EDIS / OneProvider billing) --}}
@php
    $quotaAccent = [
        'throttled' => ['bar' => 'bg-error-500', 'badge' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400', 'label' => 'Throttled'],
        'high' => ['bar' => 'bg-error-500', 'badge' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400', 'label' => 'High'],
        'warn' => ['bar' => 'bg-warning-500', 'badge' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400', 'label' => 'Warn'],
        'safe' => ['bar' => 'bg-success-500', 'badge' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400', 'label' => 'OK'],
    ];
    $quotaProvider = [
        'edis' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400',
        'oneprovider' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400',
        'ovh' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400',
    ];
    $accent = $quotaAccent[$externalUsage['status'] ?? 'safe'] ?? $quotaAccent['safe'];
    $isInventoryOnly = ($externalUsage['provider'] ?? null) === 'ovh';
@endphp
<div class="space-y-3 rounded-xl border border-gray-100 bg-gray-50/70 p-3 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex items-center justify-between gap-2">
        <p class="text-theme-xs font-semibold uppercase tracking-wide text-gray-500">Provider Billing Quota</p>
        @if ($externalUsage)
            <span class="rounded-full px-2 py-0.5 text-xs font-semibold uppercase tracking-wide {{ $quotaProvider[$externalUsage['provider']] ?? 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300' }}">
                {{ $externalUsage['provider'] }}
            </span>
        @endif
    </div>

    @if (! $externalUsage)
        <p class="flex items-center gap-2 text-theme-sm text-gray-500 dark:text-gray-400">
            <iconify-icon icon="mdi:information-outline" width="16" height="16" class="shrink-0"></iconify-icon>
            Not linked to an EDIS Global, OneProvider, or OVHcloud account — no billing usage to show for {{ $server->ip_address }}.
        </p>
    @elseif ($isInventoryOnly)
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-2xl font-bold tabular-nums text-gray-800 dark:text-white/90">Unlimited traffic</p>
                <p class="mt-0.5 text-theme-xs text-gray-400">{{ $externalUsage['name'] }}</p>
            </div>
            <div class="text-right">
                <p class="text-theme-xs uppercase tracking-wide text-gray-400">Model</p>
                <p class="text-theme-sm font-medium text-gray-700 dark:text-gray-300">{{ $externalUsage['model'] ?? '—' }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between text-theme-xs text-gray-500 dark:text-gray-400">
            <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 font-medium {{ $accent['badge'] }}">
                {{ $accent['label'] }}
            </span>
            <span class="capitalize">State: {{ $externalUsage['state'] ?? 'unknown' }}</span>
        </div>
    @else
        @php
            $usedGb = $externalUsage['used_bytes'] / 1_000_000_000;
            $limitGb = $externalUsage['unlimited'] ? null : $externalUsage['limit_bytes'] / 1_000_000_000;
            $pct = min(100, (float) $externalUsage['percent']);
        @endphp
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-2xl font-bold tabular-nums text-gray-800 dark:text-white/90">{{ number_format($usedGb, 1) }} GB</p>
                <p class="mt-0.5 text-theme-xs text-gray-400">{{ $externalUsage['name'] }}</p>
            </div>
            <div class="text-right">
                <p class="text-theme-xs uppercase tracking-wide text-gray-400">Cap</p>
                <p class="text-theme-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $limitGb !== null ? number_format($limitGb / 1000, 2).' TB' : 'Unlimited' }}
                </p>
            </div>
        </div>

        @unless ($externalUsage['unlimited'])
            <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-white/10">
                <div class="h-full rounded-full {{ $accent['bar'] }}" style="width: {{ $pct }}%"></div>
            </div>
        @endunless

        <div class="flex items-center justify-between text-theme-xs text-gray-500 dark:text-gray-400">
            <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 font-medium {{ $accent['badge'] }}">
                {{ $accent['label'] }}
            </span>
            <span>
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $externalUsage['days_remaining'] !== null ? $externalUsage['days_remaining'] : '—' }}</span>
                days to reset
            </span>
        </div>
    @endif
</div>
