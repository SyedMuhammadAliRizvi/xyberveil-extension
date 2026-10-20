@section('title', 'Manage Proxy Server')
<div>
    @if ($this->isInstalling())
        <div wire:poll.5000ms="refreshData" class="hidden" aria-hidden="true"></div>
    @endif

    <x-tailadmin.breadcrumb :items="[
        ['label' => 'Proxy Servers', 'route' => 'proxy-servers.all'],
        ['label' => 'Manage'],
    ]" />

    @php $cardClass = 'rounded-2xl border border-gray-200 bg-white transition-all duration-200 hover:border-accent hover:shadow-theme-md dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-accent'; @endphp

    {{-- Gauges --}}
    <div>
        <div class="mb-3 flex justify-end">
            <button type="button" wire:click="fetchMetrics"
                class="flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300">
                <iconify-icon icon="radix-icons:reload" width="20" height="20" wire:loading.remove wire:target="fetchMetrics" class="transition-all duration-300"></iconify-icon>
                <iconify-icon icon="radix-icons:reload" width="20" height="20" wire:loading wire:target="fetchMetrics" class="animate-spin transition-all duration-300"></iconify-icon>
                <span wire:loading.remove wire:target="fetchMetrics">Refresh</span>
                <span wire:loading wire:target="fetchMetrics">Loading...</span>
            </button>
        </div>

        <div class="mb-5 grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
            <div class="{{ $cardClass }} p-5">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                        <iconify-icon icon="fad:cpu" width="34" height="34"></iconify-icon>
                    </div>
                    <div class="mt-2 flex items-center justify-center" wire:ignore>
                        <div id="cpu-chart"></div>
                    </div>
                </div>
            </div>
            <div class="{{ $cardClass }} p-5">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                        <iconify-icon icon="ri:ram-2-line" width="34" height="34"></iconify-icon>
                    </div>
                    <div class="mt-2 flex items-center justify-center" wire:ignore>
                        <div id="ram-chart"></div>
                    </div>
                </div>
            </div>
            <div class="{{ $cardClass }} p-5">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                        <iconify-icon icon="clarity:hard-disk-line" width="34" height="34"></iconify-icon>
                    </div>
                    <div class="mt-2 flex items-center justify-center" wire:ignore>
                        <div id="disk-chart"></div>
                    </div>
                </div>
            </div>
            <div class="{{ $cardClass }} p-5">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                        <iconify-icon icon="mdi:heart-pulse" width="34" height="34"></iconify-icon>
                    </div>
                    <div class="mt-2 flex items-center justify-center" wire:ignore>
                        <div id="health-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Server status --}}
    <div class="{{ $cardClass }} mb-5 flex items-center justify-between p-5 sm:p-6">
        <div>
            <h6 class="mb-1 text-theme-sm font-medium text-gray-500 dark:text-gray-400">Server Status</h6>
            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ in_array($status, ['healthy', 'ok'], true) ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : ($status === 'degraded' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400') }}">
                {{ ucfirst($status) }}
            </span>
        </div>
        <div class="text-right">
            <p class="text-theme-xs text-gray-400">Last Updated</p>
            <p class="text-theme-sm text-gray-700 dark:text-gray-300">{{ $lastUpdated ? $lastUpdated->diffForHumans() : 'Never' }}</p>
        </div>
    </div>

    {{-- Connected users summary --}}
    <div class="mb-5 grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
        <x-tailadmin.metric-card icon="mdi:vpn" icon-class="bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400"
            label="Total Connected" :value="number_format($totalConnected)" />
        <x-tailadmin.metric-card icon="mdi:shield-key" icon-class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400"
            label="Provisioned" :value="number_format($ikev2Connected)" />
        <x-tailadmin.metric-card icon="mdi:lock-open" icon-class="bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400"
            label="Active Sessions" :value="number_format($openvpnConnected)" />
        <x-tailadmin.metric-card icon="mdi:ethernet" icon-class="bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400"
            label="Synced" :value="number_format($wireguardConnected)" />
    </div>

    <div class="mb-5 flex justify-end">
        <button type="button" wire:click="fetchConnectedUsers"
            class="flex h-10 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-theme-sm font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
            <iconify-icon icon="radix-icons:reload" width="16" height="16" wire:loading.remove wire:target="fetchConnectedUsers" class="transition-all duration-300"></iconify-icon>
            <iconify-icon icon="radix-icons:reload" width="16" height="16" wire:loading wire:target="fetchConnectedUsers" class="animate-spin transition-all duration-300"></iconify-icon>
            <span wire:loading.remove wire:target="fetchConnectedUsers">Fetch Connected Users</span>
            <span wire:loading wire:target="fetchConnectedUsers">Loading...</span>
        </button>
    </div>

    {{-- Connected users table --}}
    <div class="{{ $cardClass }} mb-5">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Connected Users</h3>
            <select wire:model.live="vpnTypeFilter"
                class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                <option value="all">All</option>
                <option value="proxy">Proxy</option>
                <option value="http">HTTP</option>
                <option value="socks">SOCKS</option>
            </select>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-3 py-3 font-medium">Name</th>
                        <th class="px-3 py-3 font-medium">IP</th>
                        <th class="px-3 py-3 font-medium">Uptime</th>
                        <th class="px-3 py-3 font-medium">VPN Type</th>
                        <th class="px-5 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @php
                        $filteredUsers = collect($connectedUsers)->filter(fn ($user) => $vpnTypeFilter === 'all' || $user['vpn_type'] === $vpnTypeFilter);
                    @endphp

                    @forelse ($filteredUsers as $index => $user)
                        <tr wire:key="connected-user-{{ $index }}" class="transition-colors hover:bg-gray-100 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ ucfirst(preg_replace('/_[^_]+$/', '', $user['name'])) }}</td>
                            <td class="px-3 py-3"><code class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $user['ip_address'] }}</code></td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user['uptime'] }}</td>
                            <td class="px-3 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $user['vpn_type'] === 'http' ? 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400' : ($user['vpn_type'] === 'socks' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400') }}">
                                    {{ ucfirst($user['vpn_type']) }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <a href=""
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                    <iconify-icon icon="ic:round-manage-accounts" width="18" height="18"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">No connected users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Server actions --}}
    <div class="{{ $cardClass }}">
        <div class="px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Server Actions</h3>
        </div>
        <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
            <div class="flex flex-wrap gap-2">
                @if (($serverData['setup_status'] ?? $server->setup_status) !== 'ready')
                    <button type="button" wire:click="runSetup"
                        class="flex items-center gap-1.5 rounded-lg border border-brand-200 px-4 py-2.5 text-theme-sm font-medium text-brand-600 transition-colors hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="mdi:download-network" width="18" height="18"></iconify-icon>
                        Run Setup
                    </button>
                @endif
                <button type="button" wire:click="syncUsers"
                    class="flex items-center gap-1.5 rounded-lg border border-blue-light-200 px-4 py-2.5 text-theme-sm font-medium text-blue-light-600 transition-colors hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                    <iconify-icon icon="mdi:account-sync" width="18" height="18"></iconify-icon>
                    Sync Users
                </button>
                <button type="button" wire:click="toggleActive"
                    class="flex items-center gap-1.5 rounded-lg border px-4 py-2.5 text-theme-sm font-medium transition-colors {{ ($serverData['is_active'] ?? $server->is_active) ? 'border-warning-200 text-warning-600 hover:bg-warning-50 dark:border-warning-500/30 dark:text-warning-400 dark:hover:bg-warning-500/10' : 'border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10' }}">
                    <iconify-icon icon="mdi:power" width="18" height="18"></iconify-icon>
                    {{ ($serverData['is_active'] ?? $server->is_active) ? 'Deactivate' : 'Activate' }}
                </button>
                @php
                    $scriptColors = [
                        'primary' => 'border-brand-200 text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10',
                        'success' => 'border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10',
                    ];
                @endphp
                @foreach ($availableScripts as $script)
                    <button type="button" wire:click="runScript('{{ $script['script'] }}')"
                        class="flex items-center gap-1.5 rounded-lg border px-4 py-2.5 text-theme-sm font-medium transition-colors {{ $scriptColors[$script['color']] ?? $scriptColors['primary'] }}">
                        <iconify-icon icon="{{ $script['icon'] }}" width="18" height="18"></iconify-icon>
                        {{ $script['label'] }}
                    </button>
                @endforeach
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-theme-xs text-gray-400">
                <iconify-icon icon="solar:info-circle-broken" width="18" height="18"></iconify-icon>
                Clicking any action button will open the script runner in a new tab.
            </div>
        </div>
    </div>
</div>

@script
    <script>
        (function() {
        var isDarkTheme = document.documentElement.classList.contains('dark');

        function createGaugeChart(element, value, label, colorStops) {
            var options = {
                series: [value],
                chart: {
                    height: 250,
                    type: "radialBar",
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        track: {
                            background: isDarkTheme ? "#333" : "rgba(15, 23, 42, 0.08)",
                            strokeWidth: '100%'
                        },
                        dataLabels: {
                            name: {
                                fontSize: "16px",
                                color: isDarkTheme ? "#999" : "#64748b",
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: "22px",
                                color: isDarkTheme ? "#fff" : "#0f172a",
                                fontWeight: 600,
                                formatter: val => val.toFixed(1) + "%"
                            }
                        }
                    }
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "dark",
                        type: "horizontal",
                        gradientToColors: colorStops ? [colorStops[colorStops.length - 1].color] : ["#00D9FF"],
                        stops: [0, 100],
                        colorStops: colorStops || [{
                                offset: 0,
                                color: "#667eea",
                                opacity: 1
                            },
                            {
                                offset: 50,
                                color: "#00D9FF",
                                opacity: 1
                            },
                            {
                                offset: 100,
                                color: "#00D9FF",
                                opacity: 1
                            }
                        ]
                    }
                },
                stroke: {
                    lineCap: "round"
                },
                labels: [label]
            };

            var chart = new ApexCharts(document.querySelector(element), options);
            chart.render();
            return chart;
        }

        // CPU Chart - Blue/Cyan gradient
        var cpuChart = createGaugeChart("#cpu-chart", {{ (float) $cpuUsage }}, "CPU Usage", [
            { offset: 0, color: "#667eea", opacity: 1 },
            { offset: 100, color: "#00D9FF", opacity: 1 }
        ]);

        // RAM Chart - Purple/Pink gradient
        var ramChart = createGaugeChart("#ram-chart", {{ (float) $ramUsage }}, "RAM Usage", [
            { offset: 0, color: "#a855f7", opacity: 1 },
            { offset: 100, color: "#ec4899", opacity: 1 }
        ]);

        // Disk Chart - Orange/Yellow gradient
        var diskChart = createGaugeChart("#disk-chart", {{ (float) $diskUsage }}, "Disk Usage", [
            { offset: 0, color: "#f59e0b", opacity: 1 },
            { offset: 100, color: "#fbbf24", opacity: 1 }
        ]);

        // Health Chart - Dynamic gradient (Red to Green)
        var healthChart = createGaugeChart("#health-chart", {{ (float) $healthScore }}, "Health Score", [
            { offset: 0, color: "#ef4444", opacity: 1 },
            { offset: 50, color: "#f59e0b", opacity: 1 },
            { offset: 100, color: "#10b981", opacity: 1 }
        ]);

        $wire.on('updateUsage', (event) => {
            let cpuUsage = parseFloat(event.cpu) || 0;
            let ramUsage = parseFloat(event.ram) || 0;
            let diskUsage = parseFloat(event.disk) || 0;
            let healthScore = parseFloat(event.health) || 0;

            if (event.cpu !== undefined) {
                cpuChart.updateSeries([cpuUsage]);
            }
            if (event.ram !== undefined) {
                ramChart.updateSeries([ramUsage]);
            }
            if (event.disk !== undefined) {
                diskChart.updateSeries([diskUsage]);
            }
            if (event.health !== undefined) {
                healthChart.updateSeries([healthScore]);
            }
        });

        $wire.on('sweetToast', (event) => {
            Swal.fire({
                text: event.message,
                icon: event.type,
                position: 'top-end',
                toast: true,
                timer: 3000,
                showConfirmButton: false
            });
        });

        $wire.on('sweetAlert', (event) => {
            Swal.fire({
                title: event.title,
                text: event.message,
                icon: event.type,
            });
        });

        $wire.on('open-script-url', (event) => {
            window.open(event.url, '_blank');
        });
        })();
    </script>
@endscript

@section('scripts')
    <script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
@endsection
