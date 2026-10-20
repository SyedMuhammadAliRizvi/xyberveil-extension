@section('title', 'All Admins')
<div x-data>
    @if (session('message'))
        <x-tailadmin.alert type="info" :message="session('message')" />
    @endif

    <x-tailadmin.breadcrumb :items="[['label' => 'Admins']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Admins</h3>
            <button type="button" wire:click="resetForm" @click="$dispatch('open-modal-admin-form')"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                Create Admin
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

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-3 py-3 font-medium">Name</th>
                        <th class="px-3 py-3 font-medium">Email</th>
                        <th class="px-3 py-3 font-medium">Last Login</th>
                        <th class="px-3 py-3 font-medium">Joined</th>
                        <th class="px-5 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($users as $user)
                        <tr wire:key="admin-{{ $user->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $user->id }}</td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user->last_login ? $user->last_login->toFormattedDateString() : 'N/A' }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->toFormattedDateString() }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="editUser({{ $user->id }})" @click="$dispatch('open-modal-admin-form')" title="Edit"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                        <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="$js.confirmDelete({{ $user->id }})" title="Delete"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">No Users found</td>
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
    <x-tailadmin.modal name="admin-form">
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ $isEdit ? 'Edit Admin' : 'Add New Admin' }}
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
            @if (! $isEdit)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Password</label>
                        <input type="text" placeholder="Password" wire:model="password" class="{{ $inputClass }}">
                        @error('password') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Confirm Password</label>
                        <input type="text" placeholder="Confirm Password" wire:model.defer="password_confirmation" class="{{ $inputClass }}">
                        @error('password_confirmation') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif
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
            <button type="button" wire:click="resetForm" @click="$dispatch('close-modal-admin-form')"
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

        $wire.on('closeModel', (event) => {
            window.dispatchEvent(new CustomEvent('close-modal-admin-form'));
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
