<?php $__env->startSection('title', 'All Tickets'); ?>
<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('message')): ?>
        <?php if (isset($component)) { $__componentOriginal784435fc350028aa1cabbd56f101a709 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal784435fc350028aa1cabbd56f101a709 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.alert','data' => ['type' => 'info','message' => session('message')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'info','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('message'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal784435fc350028aa1cabbd56f101a709)): ?>
<?php $attributes = $__attributesOriginal784435fc350028aa1cabbd56f101a709; ?>
<?php unset($__attributesOriginal784435fc350028aa1cabbd56f101a709); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal784435fc350028aa1cabbd56f101a709)): ?>
<?php $component = $__componentOriginal784435fc350028aa1cabbd56f101a709; ?>
<?php unset($__componentOriginal784435fc350028aa1cabbd56f101a709); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [['label' => 'Tickets']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Tickets']])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a)): ?>
<?php $attributes = $__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a; ?>
<?php unset($__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a)): ?>
<?php $component = $__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a; ?>
<?php unset($__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a); ?>
<?php endif; ?>

    <?php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $priorityBtnClass = [
            'low' => 'bg-success-500 hover:bg-success-600',
            'medium' => 'bg-warning-500 hover:bg-warning-600',
            'high' => 'bg-error-500 hover:bg-error-600',
        ];
    ?>

    <div class="<?php echo e($cardClass); ?>">
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr wire:key="ticket-<?php echo e($ticket->id); ?>" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400"><?php echo e($ticket->id); ?></td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($ticket->user->name); ?></td>
                            <td class="px-3 py-3">
                                <a href="<?php echo e(route('admin.ticket.details', $ticket->id)); ?>" class="text-theme-sm font-medium text-blue-light-500 hover:underline"><?php echo e($ticket->subject); ?></a>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status == 'open'): ?>
                                    <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Open</span>
                                <?php elseif($ticket->status == 'closed'): ?>
                                    <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-semibold text-error-600 dark:bg-error-500/15 dark:text-error-400">Closed</span>
                                <?php else: ?>
                                    <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-semibold text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">Pending</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                    <button type="button" @click="open = !open"
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold text-white <?php echo e($priorityBtnClass[$ticket->priority] ?? $priorityBtnClass['medium']); ?>">
                                        <?php echo e(ucfirst($ticket->priority)); ?>

                                    </button>
                                    <div x-show="open" x-cloak x-transition @click.stop
                                        class="absolute left-0 z-50 mt-1 w-32 rounded-lg border border-gray-200 bg-white py-1 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                                        <a href="#" @click.prevent="open = false" wire:click="$js.updatePriority(<?php echo e($ticket->id); ?>, 'low')" class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">Low</a>
                                        <a href="#" @click.prevent="open = false" wire:click="$js.updatePriority(<?php echo e($ticket->id); ?>, 'medium')" class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">Medium</a>
                                        <a href="#" @click.prevent="open = false" wire:click="$js.updatePriority(<?php echo e($ticket->id); ?>, 'high')" class="block px-3 py-1.5 text-theme-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/5">High</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($ticket->created_at->toFormattedDateString()); ?></td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status !== 'closed'): ?>
                                        <button type="button" wire:click="$js.updateStatus(<?php echo e($ticket->id); ?>, 'close')" title="Close"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="material-symbols:close-rounded" width="18" height="18"></iconify-icon>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status !== 'open'): ?>
                                        <button type="button" wire:click="$js.updateStatus(<?php echo e($ticket->id); ?>, 'open')" title="Reopen"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                            <iconify-icon icon="material-symbols:check-circle-outline" width="18" height="18"></iconify-icon>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status !== 'pending'): ?>
                                        <button type="button" wire:click="$js.updateStatus(<?php echo e($ticket->id); ?>, 'pending')" title="Mark as Pending"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                            <iconify-icon icon="material-symbols:hourglass-top" width="18" height="18"></iconify-icon>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">No Tickets found</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <?php echo e($tickets->links('components.tailadmin.pagination', data: ['scrollTo' => false])); ?>

        </div>
    </div>
</div>
    <?php
        $__scriptKey = '558193536-0';
        ob_start();
    ?>
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
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/all-tickets.blade.php ENDPATH**/ ?>