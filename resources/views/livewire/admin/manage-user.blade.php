@section('title', 'Manage User')
<div x-data="{ tab: '{{ $activeTab }}' }">
    <x-tailadmin.breadcrumb :items="[
        ['label' => 'Users', 'route' => 'users.all'],
        ['label' => 'Manage'],
    ]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $rowClass = 'flex items-start justify-between gap-3 border-b border-gray-100 py-2.5 text-theme-sm last:border-b-0 dark:border-gray-800';
        $labelClass = 'shrink-0 text-gray-400';
        $valueClass = 'text-right font-medium text-gray-700 dark:text-gray-300';
        $actionBtnClass = 'flex items-center gap-2 rounded-lg border px-4 py-2.5 text-theme-sm font-medium transition-colors';
    @endphp

    @if ($user->hasProxyAccess())
        {{-- Tabs --}}
        <div class="mb-5 flex gap-1 border-b border-gray-200 dark:border-gray-800">
            <button type="button" @click="tab = 'vpn'" wire:click="$set('activeTab', 'vpn')"
                class="flex items-center gap-1.5 border-b-2 px-4 py-2.5 text-theme-sm font-medium transition-colors"
                :class="tab === 'vpn' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'">
                <iconify-icon icon="mdi:shield-account" width="16" height="16"></iconify-icon>
                VPN Account
            </button>
            <button type="button" @click="tab = 'proxy'" wire:click="$set('activeTab', 'proxy')"
                class="flex items-center gap-1.5 border-b-2 px-4 py-2.5 text-theme-sm font-medium transition-colors"
                :class="tab === 'proxy' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'">
                <iconify-icon icon="mdi:server-network" width="16" height="16"></iconify-icon>
                Proxy
            </button>
        </div>
    @endif

    {{-- ── VPN tab ── --}}
    <div x-show="tab === 'vpn'">
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        {{-- Personal Info --}}
        <div class="{{ $cardClass }}">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Personal Info</h3>
            </div>
            <div class="border-t border-gray-100 px-5 py-2 dark:border-gray-800 sm:px-6">
                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Full Name</span>
                    <span class="{{ $valueClass }}">{{ $user->name }}</span>
                </div>
                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Email</span>
                    <span class="{{ $valueClass }}">{{ $user->email }}</span>
                </div>
                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Role</span>
                    <span class="{{ $valueClass }}">{{ Str::title($user->role) }}</span>
                </div>
                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Last Login</span>
                    <span class="{{ $valueClass }}">{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</span>
                </div>
                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Proxy Status</span>
                    <span class="{{ $valueClass }}">
                        @if ($proxySession)
                            <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                Connected to {{ $proxySession['server_flag'] ?? '' }} {{ $proxySession['server_name'] ?? 'Unknown server' }}
                            </span>
                            <div class="mt-1 text-theme-xs text-gray-400">since {{ \Illuminate\Support\Carbon::parse($proxySession['started_at'])->diffForHumans() }}</div>
                        @else
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Not connected</span>
                        @endif
                    </span>
                </div>
                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Email Verified</span>
                    <span class="{{ $valueClass }}">{{ $user->hasVerifiedEmail() ? $user->email_verified_at->toDayDateTimeString() : 'No' }}</span>
                </div>
                @if ($user->isBanned())
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Banned At</span>
                        <span class="{{ $valueClass }}">{{ $user->banned_at->toDayDateTimeString() }}</span>
                    </div>
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Ban Reason</span>
                        <span class="{{ $valueClass }}">{{ $user->ban_reason }}</span>
                    </div>
                @endif
                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Registered</span>
                    <span class="{{ $valueClass }}">{{ $user->created_at->toDayDateTimeString() }}</span>
                </div>
            </div>
        </div>

        {{-- Trial Information --}}
        <div class="{{ $cardClass }}">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Trial Information</h3>
            </div>
            <div class="border-t border-gray-100 px-5 py-2 dark:border-gray-800 sm:px-6">
                @php $trialDetails = $user->getTrialStatusWithDescription(); @endphp

                <div class="{{ $rowClass }}">
                    <span class="{{ $labelClass }}">Status</span>
                    <span class="{{ $valueClass }}">
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $trialDetails['status'] == 'active' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : ($trialDetails['status'] == 'expired' ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' : 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400') }}">
                            {{ $trialDetails['description'] }}
                        </span>
                    </span>
                </div>

                @if ($user->hasUsedTrial())
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Started At</span>
                        <span class="{{ $valueClass }}">{{ $user->trial_started_at ? $user->trial_started_at->toDayDateTimeString() : 'N/A' }}</span>
                    </div>
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Ends At</span>
                        <span class="{{ $valueClass }}">{{ $user->trial_ends_at ? $user->trial_ends_at->toDayDateTimeString() : 'N/A' }}</span>
                    </div>

                    @if ($user->isOnTrial())
                        <div class="{{ $rowClass }}">
                            <span class="{{ $labelClass }}">Days Remaining</span>
                            <span class="{{ $valueClass }} font-bold text-warning-600 dark:text-warning-400">{{ $trialDetails['days_remaining'] }} days</span>
                        </div>
                        <div class="py-2.5 text-theme-sm">
                            <span class="{{ $labelClass }} mb-1.5 block">Progress</span>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-white/10">
                                <div class="h-full rounded-full bg-blue-light-500" style="width: {{ $trialDetails['progress_percentage'] }}%"></div>
                            </div>
                            <div class="mt-1 text-theme-xs text-gray-400">{{ number_format($trialDetails['progress_percentage'], 1) }}% completed</div>
                        </div>
                    @endif
                @else
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Available</span>
                        <span class="{{ $valueClass }} font-bold text-success-600 dark:text-success-400">&check; User can start trial</span>
                    </div>
                @endif
            </div>
        </div>

        @if ($user->role == 'user')
            {{-- Manage Plan --}}
            <div class="{{ $cardClass }}">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Manage Plan</h3>
                </div>
                <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                    @if ($user->activeSubscription)
                        <div class="{{ $rowClass }}">
                            <span class="{{ $labelClass }}">Plan</span>
                            <span class="{{ $valueClass }}">{{ $user->activeSubscription->plan->name }}</span>
                        </div>
                        <div class="{{ $rowClass }}">
                            <span class="{{ $labelClass }}">Expires At</span>
                            <span class="{{ $valueClass }}">{{ $user->activeSubscription->ends_at->toDayDateTimeString() }}</span>
                        </div>
                        <button type="button" wire:click="$js.confirmCancelPlan()"
                            class="{{ $actionBtnClass }} mt-4 border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            Cancel Plan
                        </button>
                    @else
                        <h6 class="mb-3 text-theme-sm font-semibold text-gray-700 dark:text-gray-300">Add Plan</h6>
                        @if ($errors->any())
                            <x-tailadmin.alert type="warning" message="{{ $errors->first() }}" />
                        @endif
                        <select wire:model="selectedPlan"
                            class="mb-4 h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90">
                            <option value="" selected>Select Plan</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->discount_price ?? $plan->original_price }} / {{ $plan->invoice_period }} - {{ $plan->invoice_interval }}</option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="$js.confirmAddPlan()"
                            class="{{ $actionBtnClass }} border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            {{ $user->activePlan ? 'Extend Plan' : 'Add Plan' }}
                        </button>
                    @endif
                </div>
            </div>

            {{-- Payment History --}}
            <div class="{{ $cardClass }}">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Payment History</h3>
                </div>
                <div class="divide-y divide-gray-100 border-t border-gray-100 dark:divide-gray-800 dark:border-gray-800">
                    @forelse ($user->localSubscriptions->sortByDesc('created_at') as $purchase)
                        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 sm:px-6">
                            <div>
                                <div class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $purchase->plan->name }}</div>
                                <p class="mt-0.5 text-theme-xs text-gray-400">
                                    ({{ $purchase->starts_at->toFormattedDateString() }} - {{ $purchase->ends_at->toFormattedDateString() }}) - {{ Str::title($purchase->status) }}
                                </p>
                            </div>
                            <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">${{ $purchase->amount_paid }}</span>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-theme-sm text-gray-400 sm:px-6">No purchase history available.</p>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- User Actions --}}
        <div class="{{ $cardClass }}">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">User Actions</h3>
            </div>
            <div class="flex flex-col gap-2 border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                @if (! $user->hasUsedTrial())
                    <button type="button" wire:click="$js.confirmGrantTrial()"
                        class="{{ $actionBtnClass }} border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                        <iconify-icon icon="bx:gift" width="18" height="18"></iconify-icon>
                        Grant Trial (7 days)
                    </button>
                @else
                    @if ($user->isOnTrial())
                        <button type="button" wire:click="$js.confirmExtendTrial()"
                            class="{{ $actionBtnClass }} border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            <iconify-icon icon="bx:time" width="18" height="18"></iconify-icon>
                            Extend Trial
                        </button>
                    @endif

                    <button type="button" wire:click="$js.confirmResetTrial()"
                        class="{{ $actionBtnClass }} border-warning-200 text-warning-600 hover:bg-warning-50 dark:border-warning-500/30 dark:text-warning-400 dark:hover:bg-warning-500/10">
                        <iconify-icon icon="bx:refresh" width="18" height="18"></iconify-icon>
                        Reset Trial
                    </button>
                @endif

                @if (! $user->hasVerifiedEmail())
                    <button type="button" wire:click="verifyEmailManually"
                        class="{{ $actionBtnClass }} border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                        <iconify-icon icon="bx:check-circle" width="18" height="18"></iconify-icon>
                        Verify Email Manually
                    </button>

                    <button type="button" wire:click="resendVerificationEmail"
                        class="{{ $actionBtnClass }} border-brand-200 text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="bx:mail-send" width="18" height="18"></iconify-icon>
                        Resend Verification Email
                    </button>
                @endif

                <button type="button" wire:click="sendPasswordReset"
                    class="{{ $actionBtnClass }} border-brand-200 text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                    <iconify-icon icon="bx:key" width="18" height="18"></iconify-icon>
                    Send Password Reset Email
                </button>

                @if ($user->isBanned())
                    <button type="button" wire:click="unbanUser"
                        class="{{ $actionBtnClass }} border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                        <iconify-icon icon="bx:user-check" width="18" height="18"></iconify-icon>
                        Unban User
                    </button>
                @else
                    <button type="button" wire:click="$js.confirmBanUser()"
                        class="{{ $actionBtnClass }} border-error-200 text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                        <iconify-icon icon="bx:user-x" width="18" height="18"></iconify-icon>
                        Ban User
                    </button>
                @endif

                <button type="button" wire:click="$js.confirmDelete()"
                    class="{{ $actionBtnClass }} border-error-200 text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                    <iconify-icon icon="bx:trash" width="18" height="18"></iconify-icon>
                    Delete User
                </button>
            </div>
        </div>
    </div>

    {{-- Devices Section --}}
    <div class="{{ $cardClass }} mt-6">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="flex items-center gap-2 text-base font-semibold text-gray-800 dark:text-white/90">
                <iconify-icon icon="mdi:devices" width="20" height="20"></iconify-icon>
                Devices
                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">
                    {{ $user->hasProxyAccess() ? $user->devices->count() + $user->proxyExtensionDevices->count() : $user->devices->count() }}
                </span>
                <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-semibold text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400" title="Effective device limit">
                    Limit: {{ $user->deviceLimit() }}
                </span>
            </h3>
            @if ($user->devices->count() > 0)
                <button type="button" wire:click="$js.confirmRevokeAllDevices()"
                    class="flex items-center gap-1.5 rounded-lg border border-error-200 px-3 py-2 text-theme-xs font-medium text-error-600 transition-colors hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                    <iconify-icon icon="mdi:key-remove" width="16" height="16"></iconify-icon>
                    Revoke All Sessions
                </button>
            @endif
        </div>
        <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
            <div class="mb-4">
                <label class="mb-1.5 block text-theme-sm font-medium text-gray-600 dark:text-gray-400">
                    Custom Device Limit Override <span class="text-gray-400">(leave empty to use plan/default)</span>
                </label>
                <div class="flex items-center gap-2">
                    <input type="number" min="1" max="255" wire:model.defer="customDeviceLimit" placeholder="e.g. 3"
                        class="h-10 w-32 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90">
                    <button type="button" wire:click="setCustomDeviceLimit"
                        class="flex h-10 items-center rounded-lg border border-brand-200 px-3 text-theme-sm font-medium text-brand-600 transition-colors hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        Apply
                    </button>
                </div>
            </div>

            @if ($user->hasProxyAccess() ? ($user->devices->isEmpty() && $user->proxyExtensionDevices->isEmpty()) : $user->devices->isEmpty())
                <p class="text-theme-sm text-gray-400">No devices registered for this user.</p>
            @else
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                                <th class="px-3 py-3 font-medium">Device</th>
                                <th class="px-3 py-3 font-medium">Platform / OS</th>
                                <th class="px-3 py-3 font-medium">{{ $user->hasProxyAccess() ? 'Kind' : 'Type' }}</th>
                                <th class="px-3 py-3 font-medium">{{ $user->hasProxyAccess() ? 'IP / Key' : 'IP Address' }}</th>
                                <th class="px-3 py-3 font-medium">Location</th>
                                <th class="px-3 py-3 font-medium">Timezone</th>
                                <th class="px-3 py-3 font-medium">Last Seen</th>
                                <th class="px-3 py-3 text-center font-medium">Status</th>
                                <th class="px-3 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($user->devices as $device)
                                <tr wire:key="vpn-device-{{ $device->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
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
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                        @if ($user->hasProxyAccess())
                                            <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">VPN App</span>
                                            @if ($device->device_type)
                                                <div class="mt-0.5 text-theme-xs text-gray-400">{{ Str::title($device->device_type) }}</div>
                                            @endif
                                            @if ($device->app_version)
                                                <div class="text-theme-xs text-gray-400">v{{ $device->app_version }}</div>
                                            @endif
                                        @else
                                            {{ $device->device_type ? Str::title($device->device_type) : '—' }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm">
                                        @php $ipBlocked = $device->ip_address && isset($blockedIpSet[$device->ip_address]); @endphp
                                        <code class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $device->ip_address ?: '—' }}</code>
                                        @if ($ipBlocked)
                                            <span class="ml-1 rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-400">IP Blocked</span>
                                        @endif
                                        @if (! $user->hasProxyAccess() && $device->app_version)
                                            <div class="text-theme-xs text-gray-400">v{{ $device->app_version }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                        {{ collect([$device->city, $device->country])->filter()->implode(', ') ?: '—' }}
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $device->timezone ?: '—' }}</td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $device->last_seen_at ? $device->last_seen_at->diffForHumans() : 'Never' }}</td>
                                    <td class="px-3 py-3 text-center">
                                        @if ($device->is_blocked)
                                            <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-semibold text-error-600 dark:bg-error-500/15 dark:text-error-400">Blocked</span>
                                            @if ($device->blocked_reason)
                                                <div class="mt-0.5 text-theme-xs text-gray-400">{{ $device->blocked_reason }}</div>
                                            @endif
                                        @elseif ($device->isRevoked())
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Revoked</span>
                                        @else
                                            <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-1.5">
                                            @if ($device->is_blocked)
                                                <button type="button" wire:click="unblockDevice({{ $device->id }})" title="Unblock"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                                    <iconify-icon icon="mdi:lock-open-variant-outline" width="16" height="16"></iconify-icon>
                                                </button>
                                            @else
                                                <button type="button" wire:click="$js.confirmBlockDevice({{ $device->id }})" title="Block Device"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                                    <iconify-icon icon="mdi:block-helper" width="16" height="16"></iconify-icon>
                                                </button>
                                            @endif

                                            @if (! $device->isRevoked())
                                                <button type="button" wire:click="$js.confirmRevokeDevice({{ $device->id }})" title="Revoke Access"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">
                                                    <iconify-icon icon="mdi:key-remove" width="16" height="16"></iconify-icon>
                                                </button>
                                            @endif

                                            @if ($device->ip_address)
                                                @if ($ipBlocked)
                                                    <button type="button" wire:click="$js.confirmUnblockDeviceIp('{{ $device->ip_address }}')" title="Unblock IP"
                                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                                        <iconify-icon icon="mdi:ip-network-outline" width="16" height="16"></iconify-icon>
                                                    </button>
                                                @else
                                                    <button type="button" wire:click="$js.confirmBlockDeviceIp({{ $device->id }}, '{{ $device->ip_address }}')" title="Block IP"
                                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                                        <iconify-icon icon="mdi:ip-network-outline" width="16" height="16"></iconify-icon>
                                                    </button>
                                                @endif
                                            @endif

                                            <button type="button" wire:click="$js.confirmRemoveDevice({{ $device->id }})" title="Remove Device"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                                <iconify-icon icon="mingcute:delete-2-line" width="16" height="16"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($user->hasProxyAccess())
                                @foreach ($user->proxyExtensionDevices as $device)
                                    <tr wire:key="proxy-device-{{ $device->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                                        <td class="px-3 py-3">
                                            <div class="flex items-center gap-2">
                                                <iconify-icon icon="mdi:web" width="20" height="20" class="shrink-0 text-gray-400"></iconify-icon>
                                                <div>
                                                    <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $device->browser_name ?: 'Browser' }}</div>
                                                    <div class="text-theme-xs text-gray-400">{{ $device->browser_version ?: '—' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                            <div>{{ $device->os_name ?: '—' }}</div>
                                            <div class="text-theme-xs text-gray-400">{{ $device->os_version ?: '' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                            <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-medium text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">Extension</span>
                                            <div class="mt-0.5 text-theme-xs text-gray-400">v{{ $device->extension_version ?: '—' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm">
                                            @if ($device->ip_address)
                                                <code class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $device->ip_address }}</code>
                                                <div class="text-theme-xs text-gray-400"><code>{{ Str::limit($device->device_key, 16) }}</code></div>
                                            @else
                                                <code class="text-theme-xs text-gray-400">{{ Str::limit($device->device_key, 20) }}</code>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ collect([$device->city, $device->country])->filter()->implode(', ') ?: '—' }}</td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $device->timezone ?: '—' }}</td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $device->last_seen?->diffForHumans() ?? 'Never' }}</td>
                                        <td class="px-3 py-3 text-center">
                                            <label class="inline-flex cursor-pointer items-center gap-2">
                                                <span class="relative inline-flex h-6 w-11 shrink-0 items-center">
                                                    <input type="checkbox" @checked($device->is_allowed)
                                                        wire:change="setExtensionDeviceAllowed({{ $device->id }}, $event.target.checked)"
                                                        class="peer sr-only">
                                                    <span class="absolute inset-0 rounded-full bg-gray-200 transition-colors peer-checked:bg-brand-500 dark:bg-white/10"></span>
                                                    <span class="absolute left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                                                </span>
                                                <span class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $device->is_allowed ? 'Allowed' : 'Blocked' }}</span>
                                            </label>
                                        </td>
                                        <td class="px-3 py-3">
                                            <button type="button" wire:click="$js.confirmRemoveProxyExtensionDevice({{ $device->id }})" title="Remove extension device and force logout"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                                <iconify-icon icon="mingcute:delete-2-line" width="16" height="16"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    </div>{{-- /VPN tab --}}

    @if ($user->hasProxyAccess())
    {{-- ── Proxy tab ── --}}
    <div x-show="tab === 'proxy'" x-cloak>
        <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
            {{-- Proxy Account --}}
            <div wire:key="proxy-account-card" class="{{ $cardClass }}">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Proxy Account</h3>
                </div>
                <div class="border-t border-gray-100 px-5 py-2 dark:border-gray-800 sm:px-6">
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Member ID</span>
                        <span class="{{ $valueClass }}"><code>{{ $this->memberId }}</code></span>
                    </div>
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Linux Username</span>
                        <span class="{{ $valueClass }}"><code>{{ $user->linux_username }}</code></span>
                    </div>
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Proxy Account</span>
                        <span class="{{ $valueClass }}">
                            @php
                                $proxyBadgeClasses = [
                                    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                    'error' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                                ];
                            @endphp
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $proxyBadgeClasses[$statusColors[(string) ($proxyStatus ?? '')] ?? ''] ?? 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' }}">
                                {{ ucfirst($proxyStatus ?? 'none') }}
                            </span>
                        </span>
                    </div>
                    <div class="{{ $rowClass }}">
                        <span class="{{ $labelClass }}">Connection</span>
                        <span class="{{ $valueClass }}">
                            @if ($proxySession)
                                <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                    Connected to {{ $proxySession['server_flag'] ?? '' }} {{ $proxySession['server_name'] ?? 'Unknown server' }}
                                </span>
                                <div class="mt-1 text-theme-xs text-gray-400">since {{ \Illuminate\Support\Carbon::parse($proxySession['started_at'])->diffForHumans() }}</div>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Not connected</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Proxy Actions --}}
            <div wire:key="proxy-actions-card" class="{{ $cardClass }}">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Proxy Actions</h3>
                </div>
                <div class="flex flex-col gap-2 border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                    @if ($proxyStatus === 'pending')
                        <button type="button" wire:click="approveProxy"
                            class="{{ $actionBtnClass }} border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                            <iconify-icon icon="bx:check" width="18" height="18"></iconify-icon>
                            Approve
                        </button>
                    @endif
                    <button type="button" wire:click="$js.confirmResetProxyPassword()"
                        class="{{ $actionBtnClass }} border-brand-200 text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="bx:key" width="18" height="18"></iconify-icon>
                        Reset Proxy Password
                    </button>
                    @if ($proxyStatus === 'active')
                        <button type="button" wire:click="$js.confirmBanProxy()"
                            class="{{ $actionBtnClass }} border-error-200 text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                            <iconify-icon icon="bx:block" width="18" height="18"></iconify-icon>
                            Ban Proxy Access
                        </button>
                    @endif
                    @if ($proxyStatus === 'banned')
                        <button type="button" wire:click="unbanProxy"
                            class="{{ $actionBtnClass }} border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            <iconify-icon icon="bx:user-check" width="18" height="18"></iconify-icon>
                            Unban Proxy Access
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Connection History --}}
        <div wire:key="connection-history-card" class="{{ $cardClass }}">
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                <h3 class="flex items-center gap-2 text-base font-semibold text-gray-800 dark:text-white/90">
                    <iconify-icon icon="mdi:connection" width="20" height="20"></iconify-icon>
                    Connection History
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">{{ $connectionHistory?->total() ?? 0 }}</span>
                </h3>
                @if ($connectionHistory && $connectionHistory->isNotEmpty())
                    <select wire:key="sessions-per-page-select" wire:model.live="sessionsPerPage"
                        class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                        <option value="5">5 per page</option>
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                @endif
            </div>

            @if (! $connectionHistory || $connectionHistory->isEmpty())
                <p class="border-t border-gray-100 px-5 py-6 text-theme-sm text-gray-400 dark:border-gray-800 sm:px-6">No connection sessions recorded.</p>
            @else
                <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                                <th class="px-5 py-3 font-medium">Server</th>
                                <th class="px-3 py-3 font-medium">Client IP</th>
                                <th class="px-3 py-3 font-medium">Started</th>
                                <th class="px-3 py-3 font-medium">Duration</th>
                                <th class="px-3 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($connectionHistory as $session)
                                <tr wire:key="proxy-session-{{ $session->id }}">
                                    <td class="px-5 py-3 text-theme-sm text-gray-700 dark:text-gray-300"><x-tailadmin.flag :flag="$session->server?->flag" /> {{ $session->server?->name ?? '—' }}</td>
                                    <td class="px-3 py-3"><code class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $session->client_ip ?? '—' }}</code></td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $session->started_at?->diffForHumans() }}</td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                        @php
                                            $secs = $session->duration_seconds
                                                ?? ($session->isActive() ? (int) $session->started_at?->diffInSeconds(now()) : 0);
                                            $h = intdiv($secs, 3600);
                                            $m = intdiv($secs % 3600, 60);
                                            $s = $secs % 60;
                                        @endphp
                                        {{ sprintf('%02d:%02d:%02d', $h, $m, $s) }}
                                    </td>
                                    <td class="px-3 py-3">
                                        @if ($session->isActive())
                                            <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Live</span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Ended</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                    {{ $connectionHistory->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
                </div>
            @endif
        </div>
    </div>{{-- /Proxy tab --}}
    @endif
</div>
@script
    <script>
        $js('confirmCancelPlan', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to cancel this plan?',
                icon: 'warning',
                width: 500,
                html: `
                    <div style="margin-bottom: 20px; text-align: left;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Cancellation Reason:</label>
                        <input type="text" id="cancelReason" placeholder="Enter cancellation reason"
                               style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;
                                      font-size: 14px; background: white; color: #333; box-sizing: border-box;">
                    </div>
                    <div style="margin-top: 15px; text-align: left; display: flex; align-items: center;">
                        <input type="checkbox" id="endImmediately"
                               style="width: 18px; height: 18px; margin-right: 10px; cursor: pointer;
                                      accent-color: #dc3545;">
                        <label for="endImmediately" style="font-weight: 500; color: #dc3545; cursor: pointer; margin: 0;">
                            End subscription immediately
                        </label>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it',
                customClass: {
                    htmlContainer: 'swal2-html-container'
                },
                preConfirm: () => {
                    const reason = document.getElementById('cancelReason').value;
                    const endImmediately = document.getElementById('endImmediately').checked;

                    if (!reason.trim()) {
                        Swal.showValidationMessage('Cancellation reason is required');
                        return false;
                    }

                    return {
                        reason: reason.trim(),
                        endImmediately
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.cancelSubscription(result.value.reason, result.value.endImmediately);
                }
            });
        });

        $js('confirmAddPlan', () => {
            Swal.fire({
                title: 'Confirm Plan Change?',
                text: 'Are you sure you want to add or extend this plan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.addPlan();
                }
            });
        });

        $js('confirmBanUser', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will prevent the user from accessing the system.',
                icon: 'warning',
                input: 'text',
                inputLabel: 'Reason for banning',
                inputPlaceholder: 'Enter reason for banning',
                showCancelButton: true,
                confirmButtonText: 'Yes, ban user!',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Reason is required');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.banUser(result.value);
                }
            });
        });

        $js('confirmDelete', () => {
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
                    $wire.deleteUser();
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

        // Trial Management Functions
        $js('confirmGrantTrial', () => {
            Swal.fire({
                title: 'Grant Trial',
                text: 'How many days should the trial last?',
                icon: 'question',
                input: 'number',
                inputValue: 7,
                inputAttributes: {
                    min: 1,
                    max: 30,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonText: 'Grant Trial',
                preConfirm: (days) => {
                    if (!days || days < 1 || days > 30) {
                        Swal.showValidationMessage('Please enter a valid number of days (1-30)');
                    }
                    return days;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.grantTrial(parseInt(result.value));
                }
            });
        });

        $js('confirmExtendTrial', () => {
            Swal.fire({
                title: 'Extend Trial',
                text: 'How many additional days?',
                icon: 'question',
                input: 'number',
                inputValue: 7,
                inputAttributes: {
                    min: 1,
                    max: 30,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonText: 'Extend Trial',
                preConfirm: (days) => {
                    if (!days || days < 1 || days > 30) {
                        Swal.showValidationMessage('Please enter a valid number of days (1-30)');
                    }
                    return days;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.extendTrial(parseInt(result.value));
                }
            });
        });

        $js('confirmResetTrial', () => {
            Swal.fire({
                title: 'Reset Trial',
                text: 'This will allow the user to start a new trial. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f0ad4e',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset trial!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.resetTrial();
                }
            });
        });

        // Device Management
        $js('confirmBlockDevice', (id) => {
            Swal.fire({
                title: 'Block Device?',
                text: 'The session will be terminated and the device will be blocked.',
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
                title: 'Revoke Device Access?',
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

        $js('confirmUnblockDeviceIp', (ip) => {
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

        $js('confirmBlockDeviceIp', (id, ip) => {
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

        $js('confirmRemoveProxyExtensionDevice', (deviceId) => {
            Swal.fire({
                title: 'Remove this extension device?',
                text: 'The browser will disconnect and log out immediately.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Remove & logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.removeProxyExtensionDevice(deviceId);
                }
            });
        });

        $js('confirmRevokeAllDevices', () => {
            Swal.fire({
                title: 'Revoke All Sessions?',
                text: 'All device sessions for this user will be terminated immediately.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, revoke all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.revokeAllDevices();
                }
            });
        });

        $js('confirmResetProxyPassword', () => {
            Swal.fire({
                title: 'Reset proxy password?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Reset'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.resetProxyPassword();
                }
            });
        });

        $js('confirmBanProxy', () => {
            Swal.fire({
                title: 'Ban proxy access?',
                input: 'text',
                inputLabel: 'Reason (optional)',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ban'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.banProxy(result.value || null);
                }
            });
        });

        $wire.on('newPasswordGenerated', () => {
            Swal.fire({
                title: 'New password generated',
                html: `<code style="font-size:1.1rem">${$wire.generatedPassword}</code>`,
                icon: 'success',
                confirmButtonText: 'Copy & close'
            }).then(() => {
                if (navigator.clipboard && $wire.generatedPassword) {
                    navigator.clipboard.writeText($wire.generatedPassword);
                }
            });
        });
    </script>
@endscript
