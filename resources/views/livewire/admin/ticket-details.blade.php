@section('title', 'Manage Ticket')
<div>
    <x-tailadmin.breadcrumb :items="[
        ['label' => 'Tickets', 'route' => 'admin.tickets'],
        ['label' => 'Manage'],
    ]" />

    @php $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'; @endphp

    <div class="{{ $cardClass }} flex flex-col" style="height: calc(100vh - 220px); min-height: 500px;">
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Ticket: {{ Str::limit($ticket->subject, 50) }}</h3>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                        Status:
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $ticket->status === 'open' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : ($ticket->status === 'pending' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </button>
                    <div x-show="open" x-cloak x-transition @click.stop
                        class="absolute right-0 z-50 mt-1 w-44 rounded-lg border border-gray-200 bg-white py-1 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                        @foreach (['open', 'pending', 'closed'] as $status)
                            @if ($ticket->status !== $status)
                                <a href="#" @click.prevent="open = false" wire:click="$js.updateStatus({{ $ticket->id }}, '{{ $status }}')"
                                    class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">
                                    Mark as {{ ucfirst($status) }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                        Priority:
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $ticket->priority === 'high' ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' : ($ticket->priority === 'medium' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </button>
                    <div x-show="open" x-cloak x-transition @click.stop
                        class="absolute right-0 z-50 mt-1 w-44 rounded-lg border border-gray-200 bg-white py-1 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                        @foreach (['low', 'medium', 'high'] as $priority)
                            @if ($ticket->priority !== $priority)
                                <a href="#" @click.prevent="open = false" wire:click="$js.updatePriority({{ $ticket->id }}, '{{ $priority }}')"
                                    class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">
                                    Set to {{ ucfirst($priority) }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 space-y-4 overflow-y-auto p-5 sm:p-6">
            @forelse ($ticket->messages as $message)
                <div class="flex {{ $message->is_admin ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%]">
                        <p class="mb-1 text-theme-xs text-gray-400 {{ $message->is_admin ? 'text-right' : 'text-left' }}">
                            {{ $message->is_admin ? 'You' : $message->user->name }}, {{ $message->created_at->diffForHumans() }}
                        </p>

                        @if ($message->getMedia('attachments')->count())
                            <div class="mb-2 flex flex-wrap gap-2 {{ $message->is_admin ? 'justify-end' : 'justify-start' }}">
                                @foreach ($message->getMedia('attachments') as $media)
                                    @if ($editingMessageId === $message->id)
                                        @if (in_array($media->uuid, $existingImages))
                                            <div class="relative">
                                                <img src="{{ $media->getFullUrl() }}" alt="attachment" class="h-[70px] w-[70px] rounded-lg border border-gray-200 object-cover dark:border-gray-800">
                                                <button type="button" wire:click="$js.confirmImageDelete('{{ $media->uuid }}')"
                                                    class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-error-500 text-white">
                                                    <iconify-icon icon="mdi:close" width="12" height="12"></iconify-icon>
                                                </button>
                                            </div>
                                        @endif
                                    @else
                                        <a href="{{ $media->getFullUrl() }}" target="_blank">
                                            <img src="{{ $media->getFullUrl() }}" alt="attachment" class="h-[70px] w-[70px] rounded-lg border border-gray-200 object-cover dark:border-gray-800">
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-2 {{ $message->is_admin ? 'flex-row-reverse' : 'flex-row' }}">
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button type="button" @click="open = !open"
                                    class="flex h-7 w-7 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5">
                                    <iconify-icon icon="bi:three-dots-vertical" width="16" height="16"></iconify-icon>
                                </button>
                                <div x-show="open" x-cloak x-transition @click.stop
                                    class="absolute {{ $message->is_admin ? 'right-0' : 'left-0' }} z-50 mt-1 w-36 rounded-lg border border-gray-200 bg-white py-1 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                                    <button type="button" @click="open = false" wire:click="$js.copyMessage({{ $message->id }})"
                                        class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">
                                        <iconify-icon icon="solar:copy-line-duotone" width="16" height="16"></iconify-icon>
                                        Copy
                                    </button>
                                    @if ($message->is_admin && $loop->last)
                                        <button type="button" @click="open = false" wire:click="startEditing({{ $message->id }})"
                                            class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">
                                            <iconify-icon icon="material-symbols:edit" width="16" height="16"></iconify-icon>
                                            Edit
                                        </button>
                                        <button type="button" @click="open = false" wire:click="$js.confirmMessageDelete({{ $message->id }})"
                                            class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-theme-sm text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="mingcute:delete-2-line" width="16" height="16"></iconify-icon>
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if ($editingMessageId === $message->id)
                                <div class="flex flex-col gap-2">
                                    <textarea wire:model.defer="editMessageContent" rows="2"
                                        class="w-72 rounded-lg border border-gray-200 bg-transparent px-3 py-2 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90"></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" wire:click="updateMessage"
                                            class="rounded-lg bg-success-500 px-3 py-1.5 text-theme-xs font-medium text-white hover:bg-success-600">Save</button>
                                        <button type="button" wire:click="cancelEditing"
                                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-theme-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">Cancel</button>
                                    </div>
                                </div>
                            @else
                                <p id="chat-message-{{ $message->id }}"
                                    class="rounded-2xl px-4 py-2.5 text-theme-sm {{ $message->is_admin ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-gray-300' }}">
                                    {{ $message->message }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-theme-sm text-gray-400">No messages yet</p>
            @endforelse
        </div>

        {{-- Footer / reply box --}}
        <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:px-6" x-data>
            @if ($uploadedImagesCount > 0)
                <div class="mb-2 flex items-center gap-2 text-theme-xs text-blue-light-500">
                    <span>{{ $uploadedImagesCount }} image{{ $uploadedImagesCount > 1 ? 's' : '' }} attached to this reply</span>
                    <button type="button" @click="$dispatch('open-modal-ticket-image-upload')" class="underline">View</button>
                    <button type="button" wire:click="resetForm" class="text-error-500 underline">Clear</button>
                </div>
            @endif

            <div class="flex items-center gap-2">
                <textarea wire:model.defer="message" placeholder="Type your reply..." rows="2"
                    @keydown.enter="!$event.shiftKey && ($event.preventDefault(), $wire.sendReply())"
                    class="h-auto flex-1 rounded-lg border border-gray-200 bg-transparent px-3 py-2.5 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90"></textarea>
                <button type="button" @click="$dispatch('open-modal-ticket-image-upload')" title="Attach images"
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">
                    <iconify-icon icon="bx:file" width="20" height="20"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    {{-- Image upload modal --}}
    <x-tailadmin.modal name="ticket-image-upload">
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Upload Images</h4>

        <div>
            <label class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Image (Max 5)</label>
            <x-filepond::upload x-ref="filepond" wire:model="attachments"
                allowImageValidateSize="true" maxFileSize="20MB" maxParallelUploads="5"
                allowFileTypeValidation="true" maxFiles="5" allowReorder="true"
                acceptedFileTypes="image/jpeg, image/png, image/jpg" multiple />
            @error('attachments') <p class="mt-1 text-theme-xs text-error-500">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" @click="$dispatch('close-modal-ticket-image-upload')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Close
            </button>
            <button type="button" wire:click="resetForm"
                class="rounded-lg bg-success-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-success-600">
                Clear Images
            </button>
        </div>
    </x-tailadmin.modal>
</div>
@script
    <script>
        $js('updateStatus', (id, status) => {
            Swal.fire({
                title: `Change ticket status to "${status}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.updateStatus(id, status);
                }
            });
        });

        $js('updatePriority', (id, priority) => {
            Swal.fire({
                title: `Set priority to "${priority}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, set it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.updatePriority(id, priority);
                }
            });
        });

        $js('copyMessage', (messageId) => {
            const messageContent = document.getElementById('chat-message-' + messageId).innerText;

            navigator.clipboard.writeText(messageContent).then(() => {
                Swal.fire({
                    text: 'Message copied successfully!',
                    icon: 'success',
                    position: 'top-end',
                    toast: true,
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch((err) => {
                Swal.fire({
                    text: 'Failed to copy message!',
                    icon: 'error',
                    position: 'top-end',
                    toast: true,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });

        $js('confirmImageDelete', (imageId) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This image will be permanently deleted and cannot be recovered!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteImage(imageId);
                }
            });
        });

        $js('confirmMessageDelete', (messageId) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This message will be permanently deleted and cannot be recovered!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteMessage(messageId);
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

        $wire.on('sweetToast', (event) => {
            Swal.fire({
                text: event.message,
                icon: event.type,
                position: 'top-end',
                toast: true,
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endscript
@section('scripts')
    @filepondScripts
@endsection
