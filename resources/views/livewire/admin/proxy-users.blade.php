@section('title', 'Proxy Users')
<div wire:poll.visible.30000ms="loadUsers">
    <x-tailadmin.breadcrumb :items="[['label' => 'Proxy Users']]" />

    @php $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'; @endphp

    {{-- Summary cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
        <x-tailadmin.metric-card icon="mdi:account-multiple" icon-class="bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400"
            label="Total Users" :value="$this->stats['total']" />
        <x-tailadmin.metric-card icon="mdi:account-check" icon-class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400"
            label="Active" :value="$this->stats['active']" />
        <x-tailadmin.metric-card icon="mdi:account-clock" icon-class="bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400"
            label="Pending Approval" :value="$this->stats['pending']" />
        <x-tailadmin.metric-card icon="mdi:account-cancel" icon-class="bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400"
            label="Banned" :value="$this->stats['banned']" />
    </div>

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">SecureProxy Users</h3>
            <button type="button" wire:click="loadUsers" wire:loading.attr="disabled" wire:target="loadUsers"
                class="flex h-10 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                <iconify-icon icon="radix-icons:reload" width="16" height="16" wire:loading.remove wire:target="loadUsers"></iconify-icon>
                <iconify-icon icon="radix-icons:reload" width="16" height="16" wire:loading wire:target="loadUsers" class="animate-spin"></iconify-icon>
                <span wire:loading.remove wire:target="loadUsers">Refresh</span>
                <span wire:loading wire:target="loadUsers">Refreshing…</span>
            </button>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            @if ($loadError)
                <x-tailadmin.alert type="danger" :message="$loadError" />
            @endif

            <div class="flex flex-wrap items-center justify-between gap-3">
                <select wire:model.live="statusFilter"
                    class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                    <option value="banned">Banned</option>
                </select>
                <label class="relative block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                    </span>
                    <input type="text" wire:model.live.500ms="search" placeholder="Search email / name / username..."
                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-72">
                </label>
            </div>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">User</th>
                        <th class="px-3 py-3 font-medium">Linux Username</th>
                        <th class="px-3 py-3 font-medium">Role</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-3 py-3 font-medium">Sessions</th>
                        <th class="px-3 py-3 font-medium">Created</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($filteredUsers as $user)
                        <tr wire:key="proxy-user-{{ $user['id'] }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-theme-xs font-semibold text-gray-700 dark:bg-white/5 dark:text-gray-300">
                                        {{ mb_strtoupper(mb_substr($user['display_name'] ?: $user['email'], 0, 1)) }}
                                    </span>
                                    <div>
                                        <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $user['display_name'] }}</div>
                                        <div class="text-theme-xs text-gray-400">{{ $user['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3"><code class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $user['linux_username'] }}</code></td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user['role'] }}</td>
                            <td class="px-3 py-3 text-center">
                                @php $statusColors = ['pending' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400', 'active' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400', 'banned' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400']; @endphp
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $statusColors[$user['status']] ?? 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' }}">
                                    {{ ucfirst($user['status']) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user['session_count'] }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ \Illuminate\Support\Carbon::parse($user['created_at'])->diffForHumans() }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('proxy-user.manage', $user['id']) }}" title="Manage"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                        <iconify-icon icon="ic:round-manage-accounts" width="18" height="18"></iconify-icon>
                                    </a>
                                    @if ($user['status'] === 'pending')
                                        <button type="button" wire:click="approve({{ $user['id'] }})" title="Approve"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                            <iconify-icon icon="mdi:check" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif
                                    @if ($user['status'] === 'active')
                                        <button type="button" wire:click="$js.confirmBan({{ $user['id'] }})" title="Ban"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                            <iconify-icon icon="mdi:block-helper" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif
                                    @if ($user['status'] === 'banned')
                                        <button type="button" wire:click="unban({{ $user['id'] }})" title="Unban"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                            <iconify-icon icon="mdi:account-check" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif
                                    <button type="button" wire:click="$js.confirmResetPassword({{ $user['id'] }})" title="Reset password"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">
                                        <iconify-icon icon="mdi:key-variant" width="18" height="18"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="$js.confirmDeleteUser({{ $user['id'] }})" title="Delete"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">
                                <iconify-icon icon="mdi:account-off" width="32" height="32" class="mx-auto mb-2 block opacity-50"></iconify-icon>
                                {{ $loadError ? 'Unable to load proxy users.' : 'No proxy users found.' }}
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
        $js('confirmBan', (id) => {
            Swal.fire({
                title: 'Ban this user?',
                input: 'text',
                inputLabel: 'Reason (optional)',
                inputPlaceholder: 'e.g. abuse, payment issue...',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ban user'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.ban(id, result.value || null);
                }
            });
        });

        $js('confirmDeleteUser', (id) => {
            Swal.fire({
                title: 'Delete this user?',
                text: 'This permanently removes the user and de-provisions them from every proxy server.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.delete(id);
                }
            });
        });

        $js('confirmResetPassword', (id) => {
            Swal.fire({
                title: 'Reset this user\'s proxy password?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Reset password'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.resetPassword(id);
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

        $wire.on('sweetAlert', (event) => {
            Swal.fire({
                title: event.title,
                text: event.message,
                icon: event.type,
                timer: 2500,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        });
    </script>
@endscript
