@section('title', 'All Tickets')
<div>
    @if (session('message'))
        <x-tailadmin.alert type="info" :message="session('message')" />
    @endif

    <x-tailadmin.breadcrumb :items="[['label' => 'Tickets']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $priorityBtnClass = [
            'low' => 'bg-success-500 hover:bg-success-600',
            'medium' => 'bg-warning-500 hover:bg-warning-600',
            'high' => 'bg-error-500 hover:bg-error-600',
        ];
    @endphp

    <div class="{{ $cardClass }}">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Tickets</h3>
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
                        <div class="space-y-3">
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Status</label>
                                <select wire:model.live="status"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>All</option>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Priority</label>
                                <select wire:model.live="priority"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>All</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
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
                        <th class="px-3 py-3 font-medium">Subject</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-3 py-3 font-medium">Priority</th>
                        <th class="px-3 py-3 font-medium">Created</th>
                        <th class="px-5 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($tickets as $ticket)
                        <tr wire:key="ticket-{{ $ticket->id }}" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">{{ $ticket->id }}</td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $ticket->user->name }}</td>
                            <td class="px-3 py-3">
                                <a href="{{ route('admin.ticket.details', $ticket->id) }}" class="text-theme-sm font-medium text-blue-light-500 hover:underline">{{ $ticket->subject }}</a>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if ($ticket->status == 'open')
                                    <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Open</span>
                                @elseif ($ticket->status == 'closed')
                                    <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-semibold text-error-600 dark:bg-error-500/15 dark:text-error-400">Closed</span>
                                @else
                                    <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-semibold text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">Pending</span>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                    <button type="button" @click="open = !open"
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold text-white {{ $priorityBtnClass[$ticket->priority] ?? $priorityBtnClass['medium'] }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </button>
                                    <div x-show="open" x-cloak x-transition @click.stop
                                        class="absolute left-0 z-50 mt-1 w-32 rounded-lg border border-gray-200 bg-white py-1 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                                        <a href="#" @click.prevent="open = false" wire:click="$js.updatePriority({{ $ticket->id }}, 'low')" class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">Low</a>
                                        <a href="#" @click.prevent="open = false" wire:click="$js.updatePriority({{ $ticket->id }}, 'medium')" class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">Medium</a>
                                        <a href="#" @click.prevent="open = false" wire:click="$js.updatePriority({{ $ticket->id }}, 'high')" class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">High</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">{{ $ticket->created_at->toFormattedDateString() }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    @if ($ticket->status !== 'closed')
                                        <button type="button" wire:click="$js.updateStatus({{ $ticket->id }}, 'close')" title="Close"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="material-symbols:close-rounded" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif
                                    @if ($ticket->status !== 'open')
                                        <button type="button" wire:click="$js.updateStatus({{ $ticket->id }}, 'open')" title="Reopen"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                            <iconify-icon icon="material-symbols:check-circle-outline" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif
                                    @if ($ticket->status !== 'pending')
                                        <button type="button" wire:click="$js.updateStatus({{ $ticket->id }}, 'pending')" title="Mark as Pending"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                            <iconify-icon icon="material-symbols:hourglass-top" width="18" height="18"></iconify-icon>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">No Tickets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            {{ $tickets->links('components.tailadmin.pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
@script
    <script>
        $js('updateStatus', (id, status) => {
            let actionText = status === 'open' ? 'Reopen' : (status === 'pending' ? 'mark as pending' : 'close');
            let actionBtnText = status === 'open' ? 'Yes, Reopen it!' : (status === 'pending' ?
                'Yes, Mark as Pending!' : 'Yes, Close it!');

            Swal.fire({
                title: `Are you sure you want to ${actionText} this Ticket?`,
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: actionBtnText
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.updateStatus(id, status);
                }
            });
        });

        $js('updatePriority', (id, priority) => {
            let actionText = `mark as ${priority.charAt(0).toUpperCase() + priority.slice(1)}`;
            let confirmText = `Yes, ${actionText}!`;

            Swal.fire({
                title: `Are you sure you want to ${actionText} priority?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmText
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.updatePriority(id, priority);
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
    </script>
@endscript
