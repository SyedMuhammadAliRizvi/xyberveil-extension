@section('title', 'Notifications')
<div x-data>
    @if (session('message'))
        <x-tailadmin.alert type="info" :message="session('message')" />
    @endif

    <x-tailadmin.breadcrumb :items="[['label' => 'Notifications']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Notifications</h3>
            <button type="button" wire:click="resetForm" @click="$dispatch('open-modal-notification-form')"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                Create Notification
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
            <label class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                </span>
                <input type="text" wire:model.live.500ms="search" placeholder="Search..."
                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-56">
            </label>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-3 py-3 font-medium">Title</th>
                        <th class="px-3 py-3 font-medium">Message</th>
                        <th class="px-3 py-3 font-medium">Created At</th>
                        <th class="px-5 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($notifications as $notification)
                        <tr wire:key="notification-{{ $notification->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $notification->id }}</td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $notification->title }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ Str::limit($notification->message, 60) }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $notification->created_at->toFormattedDateString() }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="editNotification({{ $notification->id }})" @click="$dispatch('open-modal-notification-form')" title="Edit"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                        <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="$js.confirmDelete({{ $notification->id }})" title="Delete"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No notifications found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $notifications->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Create / Edit modal --}}
    <x-tailadmin.modal name="notification-form">
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ $isEdit ? 'Edit Notification' : 'Add New Notification' }}
        </h4>

        <form wire:submit.prevent="saveNotification" class="space-y-4">
            <div>
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" placeholder="Title" wire:model.defer="title" class="{{ $inputClass }}">
                @error('title') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Message</label>
                <textarea placeholder="Message" wire:model.defer="message" rows="4" class="{{ $inputClass }} h-auto py-2.5"></textarea>
                @error('message') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" wire:click="resetForm" @click="$dispatch('close-modal-notification-form')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Close
            </button>
            <button type="button" wire:click="saveNotification"
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
                    $wire.deleteNotification(id);
                }
            });
        });

        $wire.on('closeModel', (event) => {
            window.dispatchEvent(new CustomEvent('close-modal-notification-form'));
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
