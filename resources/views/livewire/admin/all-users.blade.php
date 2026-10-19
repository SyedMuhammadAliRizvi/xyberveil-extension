@section('title', 'Users')
<div x-data>
    @if (session('message'))
        <x-tailadmin.alert type="info" :message="session('message')" />
    @endif

    <x-tailadmin.breadcrumb :items="[['label' => 'Users']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Users</h3>
            <button type="button" wire:click="resetForm" @click="$dispatch('open-modal-user-form')"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                Create User
            </button>
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
                    <input type="text" wire:model.live.500ms="search" placeholder="Search name, email, username..."
                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-64">
                </label>

                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" @click="open = !open"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                        <iconify-icon icon="line-md:filter" width="20" height="20"></iconify-icon>
                    </button>
                    <div x-show="open" x-cloak x-transition @click.stop
                        class="absolute right-0 z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white p-4 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-3 flex items-center justify-between">
                            <h6 class="text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Filters</h6>
                            <button type="button" wire:click="resetFilters" class="text-theme-xs font-medium text-error-500 hover:underline">Reset</button>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Proxy Status</label>
                                <select wire:model.live="proxyStatus"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="">All</option>
                                    <option value="connected">Connected</option>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="banned">Banned</option>
                                    <option value="none">No Proxy</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Email Verified</label>
                                <select wire:model.live="emailVerified"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="">All</option>
                                    <option value="1">Verified</option>
                                    <option value="0">Not Verified</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Registered From</label>
                                    <input type="date" wire:model.live="registeredStart"
                                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-2 text-theme-xs text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                </div>
                                <div>
                                    <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Registered To</label>
                                    <input type="date" wire:model.live="registeredEnd"
                                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-2 text-theme-xs text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Last Login From</label>
                                    <input type="date" wire:model.live="lastLoginStart"
                                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-2 text-theme-xs text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                </div>
                                <div>
                                    <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Last Login To</label>
                                    <input type="date" wire:model.live="lastLoginEnd"
                                        class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-2 text-theme-xs text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                </div>
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
                        <th class="px-3 py-3 font-medium">Name</th>
                        <th class="px-3 py-3 font-medium">Email</th>
                        <th class="px-3 py-3 font-medium">Plan</th>
                        <th class="px-3 py-3 text-center font-medium">Proxy</th>
                        <th class="px-3 py-3 font-medium">Last Login</th>
                        <th class="px-3 py-3 font-medium">Joined</th>
                        <th class="px-5 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($users as $user)
                        @php
                            $proxyLabel = 'No';
                            $proxyBadge = 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400';
                            $status = $user->proxyStatus();
                            if ($status === 'banned') {
                                $proxyLabel = 'Banned';
                                $proxyBadge = 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400';
                            } elseif ($status === 'pending') {
                                $proxyLabel = 'Pending';
                                $proxyBadge = 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400';
                            } elseif (isset($activeProxyEmails[$user->email])) {
                                $proxyLabel = 'Connected';
                                $proxyBadge = 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400';
                            } elseif ($status === 'active') {
                                $proxyLabel = 'Active';
                                $proxyBadge = 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400';
                            } elseif ($status) {
                                $proxyLabel = ucfirst($status);
                            }
                        @endphp
                        <tr wire:key="user-{{ $user->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $user->id }}</td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user->activeSubscription?->plan?->name ?? 'Free' }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $proxyBadge }}">{{ $proxyLabel }}</span>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                @if ($user->last_login)
                                    {{ $user->last_login->diffForHumans() }}
                                @elseif ($user->hasProxyAccess())
                                    {{ $user->proxy_sessions_count }} session(s)
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->toFormattedDateString() }}</td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    @if ($user->slug)
                                        <a href="{{ route('user.manage', $user->slug) }}" title="Manage user (VPN + Proxy)"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                            <iconify-icon icon="ic:round-manage-accounts" width="18" height="18"></iconify-icon>
                                        </a>
                                    @endif

                                    @if ($user->hasProxyAccess())
                                        @if ($status === 'pending')
                                            <button type="button" wire:click="approveProxyUser({{ $user->id }})" title="Approve proxy"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                                <iconify-icon icon="mdi:check" width="18" height="18"></iconify-icon>
                                            </button>
                                        @endif
                                    @endif

                                    <button type="button" wire:click="editUser({{ $user->id }})" @click="$dispatch('open-modal-user-form')" title="Edit"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                        <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                    </button>

                                    @if ($user->hasProxyAccess())
                                        <button type="button" wire:click="$js.confirmDeleteProxyUser({{ $user->id }})"
                                            title="{{ $user->devices_count > 0 ? 'Remove proxy access' : 'Delete user' }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="{{ $user->devices_count > 0 ? 'mdi:shield-off' : 'mingcute:delete-2-line' }}" width="18" height="18"></iconify-icon>
                                        </button>
                                    @else
                                        <button type="button" wire:click="$js.confirmDelete({{ $user->id }})" title="Delete user"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif

                                    @if ($user->hasProxyAccess() && $user->devices_count > 0)
                                        <button type="button" wire:click="$js.confirmDelete({{ $user->id }})" title="Delete entire account"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-sm text-gray-400">No Users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $users->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Create / Edit modal --}}
    <x-tailadmin.modal name="user-form">
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ $isEdit ? 'Edit User' : 'Add New User' }}
        </h4>

        <form wire:submit.prevent="saveUser" class="space-y-4">
            <div>
                <label class="{{ $labelClass }}">Name</label>
                <input type="text" placeholder="Name" wire:model.defer="name" class="{{ $inputClass }}">
                @error('name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Email</label>
                <input type="email" placeholder="Email" wire:model.defer="email" class="{{ $inputClass }}">
                @error('email') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Password{{ $isEdit ? ' (leave blank to keep current)' : '' }}</label>
                    <input type="password" placeholder="Password" wire:model="password" class="{{ $inputClass }}">
                    @error('password') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Confirm Password</label>
                    <input type="password" placeholder="Confirm Password" wire:model.defer="password_confirmation" class="{{ $inputClass }}">
                    @error('password_confirmation') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
            @if ($isEdit)
                <div>
                    <label class="{{ $labelClass }}">Role</label>
                    <select wire:model.defer="role" class="{{ $inputClass }}">
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    @error('role') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            @endif
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" wire:click="resetForm" @click="$dispatch('close-modal-user-form')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Close
            </button>
            <button type="button" wire:click="saveUser"
                class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                {{ $isEdit ? 'Update' : 'Save' }}
            </button>
        </div>
    </x-tailadmin.modal>
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
                    $wire.deleteUser(id);
                }
            });
        });

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
                    $wire.banProxyUser(id, result.value || null);
                }
            });
        });

        $js('confirmDeleteProxyUser', (id) => {
            Swal.fire({
                title: 'Remove proxy access?',
                text: 'Proxy credentials will be removed from all servers. VPN account may be kept if the user has app devices.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteProxyUser(id);
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
                    $wire.resetProxyPassword(id);
                }
            });
        });

        $wire.on('closeModel', (event) => {
            window.dispatchEvent(new CustomEvent('close-modal-user-form'));
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
                timer: event.type === 'success' && event.title === 'Success!' ? 2000 : 2500,
                toast: event.title !== 'Success!',
                position: event.title !== 'Success!' ? 'top-end' : undefined,
                showConfirmButton: event.title === 'Success!'
            });
        });
    </script>
@endscript
