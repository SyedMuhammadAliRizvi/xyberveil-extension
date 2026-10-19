<?php $__env->startSection('title', 'All Plans'); ?>
<div x-data>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [['label' => 'Plans']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Plans']])]); ?>
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
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    ?>

    <div class="<?php echo e($cardClass); ?>">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Plans</h3>
            <button type="button" wire:click="resetForm" @click="$dispatch('open-modal-plan-form')"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                Create Plan
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
                        <div class="space-y-3">
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Max Price</label>
                                <select wire:model.live="priceFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Max Price</option>
                                    <option value="10">Under $10</option>
                                    <option value="20">Under $20</option>
                                    <option value="50">Under $50</option>
                                    <option value="100">Under $100</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Type</label>
                                <select wire:model.live="typeFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Type</option>
                                    <option value="regular">Regular</option>
                                    <option value="dedicated">Dedicated</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-theme-xs font-medium text-gray-600 dark:text-gray-400">Duration Unit</label>
                                <select wire:model.live="durationUnitFilter"
                                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                                    <option value="" selected>Duration Unit</option>
                                    <option value="day">Day</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                    <option value="year">Year</option>
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
                        <th class="px-3 py-3 font-medium">Name</th>
                        <th class="px-3 py-3 font-medium">Type</th>
                        <th class="px-3 py-3 font-medium">Price</th>
                        <th class="px-3 py-3 font-medium">Billing</th>
                        <th class="px-3 py-3 text-center font-medium">Subscriptions</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-3 py-3 text-center font-medium">Best Deal</th>
                        <th class="px-3 py-3 font-medium">Created</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr wire:key="plan-<?php echo e($plan->id); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['transition-colors', 'hover:bg-gray-200 dark:hover:bg-white/5', 'opacity-50' => $plan->trashed()]); ?>">
                            <td class="px-5 py-3 text-theme-sm text-gray-500 dark:text-gray-400">#PKG-<?php echo e($plan->id); ?></td>
                            <td class="px-3 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($plan->name); ?></td>
                            <td class="px-3 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($plan->type === 'dedicated' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400'); ?>">
                                    <?php echo e(ucfirst($plan->type)); ?>

                                </span>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->discount_price): ?>
                                    <span class="text-gray-400 line-through"><?php echo e($plan->getCurrencySymbol()); ?><?php echo e($plan->original_price); ?></span>
                                    <span class="font-semibold text-gray-800 dark:text-white/90"><?php echo e($plan->getCurrencySymbol()); ?><?php echo e($plan->discount_price); ?></span>
                                <?php else: ?>
                                    <?php echo e($plan->getCurrencySymbol()); ?><?php echo e($plan->original_price); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($plan->getBillingLabel()); ?></td>
                            <td class="px-3 py-3 text-center text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($plan->subscriptions_count); ?></td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($plan->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400'); ?>">
                                    <?php echo e($plan->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($plan->is_best_deal ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400'); ?>">
                                    <?php echo e($plan->is_best_deal ? 'Yes' : 'No'); ?>

                                </span>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($plan->created_at->format('d M, Y')); ?></td>
                            <td class="px-5 py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->trashed()): ?>
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="restorePlan(<?php echo e($plan->id); ?>)" title="Restore"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                            <iconify-icon icon="mdi:restore" width="18" height="18"></iconify-icon>
                                        </button>
                                        <button type="button" wire:click="$js.confirmForceDelete(<?php echo e($plan->id); ?>)" title="Delete Permanently"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="editPlan(<?php echo e($plan->id); ?>)" @click="$dispatch('open-modal-plan-form')" title="Edit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                            <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                        </button>
                                        <button type="button" wire:click="$js.confirmDelete(<?php echo e($plan->id); ?>)" title="Delete"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                            <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                        </button>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-5 py-10 text-center text-sm text-gray-400">No Plans found</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <?php echo e($plans->links('components.tailadmin.pagination', data: ['scrollTo' => false])); ?>

        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal806379accb82f5815113f9f84fdc97f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal806379accb82f5815113f9f84fdc97f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.modal','data' => ['name' => 'plan-form','maxWidth' => 'max-w-[640px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plan-form','max-width' => 'max-w-[640px]']); ?>
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
            <?php echo e($isEdit ? 'Edit Plan' : 'Add New Plan'); ?>

        </h4>

        <form wire:submit.prevent="savePlan" class="space-y-4">
            <div class="rounded-lg border border-blue-light-200 bg-blue-light-50 px-4 py-3 text-theme-sm text-blue-light-700 dark:border-blue-light-500/30 dark:bg-blue-light-500/10 dark:text-blue-light-400">
                Please write the period fields (trial, billing, grace) in number of days, weeks, months, or years as appropriate.
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">Name</label>
                <input type="text" wire:model.defer="name" class="<?php echo e($inputClass); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">Description</label>
                <textarea wire:model.defer="description" rows="2" class="<?php echo e($inputClass); ?> h-auto py-2.5"></textarea>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">Type</label>
                <select wire:model.defer="type" class="<?php echo e($inputClass); ?>">
                    <option value="regular">Regular</option>
                    <option value="dedicated">Dedicated</option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="<?php echo e($labelClass); ?>">Original Price</label>
                    <input type="number" step="0.01" placeholder="Enter original price" wire:model.defer="original_price" class="<?php echo e($inputClass); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['original_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="<?php echo e($labelClass); ?>">Discount Price</label>
                    <input type="number" step="0.01" placeholder="Enter discount price (optional)" wire:model.defer="discount_price" class="<?php echo e($inputClass); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="<?php echo e($labelClass); ?>">Trial Period</label>
                    <input type="number" placeholder="Enter trial period in days" wire:model.defer="trial_period" class="<?php echo e($inputClass); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['trial_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="<?php echo e($labelClass); ?>">Trial Interval</label>
                    <select wire:model.defer="trial_interval" class="<?php echo e($inputClass); ?>">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['trial_interval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="<?php echo e($labelClass); ?>">Invoice Period</label>
                    <input type="number" wire:model.defer="invoice_period" class="<?php echo e($inputClass); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['invoice_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="<?php echo e($labelClass); ?>">Invoice Interval</label>
                    <select wire:model.defer="invoice_interval" class="<?php echo e($inputClass); ?>">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['invoice_interval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="<?php echo e($labelClass); ?>">Grace Period</label>
                    <input type="number" placeholder="Enter grace period in days" wire:model.defer="grace_period" class="<?php echo e($inputClass); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['grace_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="<?php echo e($labelClass); ?>">Grace Interval</label>
                    <select wire:model.defer="grace_interval" class="<?php echo e($inputClass); ?>">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['grace_interval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">Stripe Price ID</label>
                <input type="text" wire:model.defer="stripe_price_id" class="<?php echo e($inputClass); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['stripe_price_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">App Store Product ID</label>
                <input type="text" wire:model.defer="appstore_product_id" class="<?php echo e($inputClass); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['appstore_product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">Max Devices <span class="text-gray-400">(leave empty for unlimited)</span></label>
                <input type="number" min="1" max="255" placeholder="e.g. 3" wire:model.defer="max_devices" class="<?php echo e($inputClass); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['max_devices'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="flex flex-wrap gap-x-6 gap-y-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                <?php if (isset($component)) { $__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.toggle','data' => ['wireModel' => 'is_active','label' => 'Is Active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-model' => 'is_active','label' => 'Is Active']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d)): ?>
<?php $attributes = $__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d; ?>
<?php unset($__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d)): ?>
<?php $component = $__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d; ?>
<?php unset($__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.toggle','data' => ['wireModel' => 'is_best_deal','label' => 'Is Best Deal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-model' => 'is_best_deal','label' => 'Is Best Deal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d)): ?>
<?php $attributes = $__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d; ?>
<?php unset($__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d)): ?>
<?php $component = $__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d; ?>
<?php unset($__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d); ?>
<?php endif; ?>
            </div>
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" wire:click="resetForm" @click="$dispatch('close-modal-plan-form')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Close
            </button>
            <button type="button" wire:click="savePlan"
                class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <?php echo e($isEdit ? 'Update' : 'Save'); ?>

            </button>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal806379accb82f5815113f9f84fdc97f6)): ?>
<?php $attributes = $__attributesOriginal806379accb82f5815113f9f84fdc97f6; ?>
<?php unset($__attributesOriginal806379accb82f5815113f9f84fdc97f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal806379accb82f5815113f9f84fdc97f6)): ?>
<?php $component = $__componentOriginal806379accb82f5815113f9f84fdc97f6; ?>
<?php unset($__componentOriginal806379accb82f5815113f9f84fdc97f6); ?>
<?php endif; ?>
</div>
    <?php
        $__scriptKey = '2956863833-0';
        ob_start();
    ?>
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
                    $wire.deletePlan(id);
                }
            });
        });

        $js('confirmForceDelete', (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action is irreversible and will permanently delete the plan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it permanently!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.forceDeletePlan(id);
                }
            });
        });

        $wire.on('closeModel', (event) => {
            window.dispatchEvent(new CustomEvent('close-modal-plan-form'));
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
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/all-plans.blade.php ENDPATH**/ ?>