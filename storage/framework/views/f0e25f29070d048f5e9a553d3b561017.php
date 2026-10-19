<?php $__env->startSection('title', 'App Version Management'); ?>
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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <?php if (isset($component)) { $__componentOriginal784435fc350028aa1cabbd56f101a709 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal784435fc350028aa1cabbd56f101a709 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.alert','data' => ['type' => 'danger','message' => session('error')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('error'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [['label' => 'App Version Management']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'App Version Management']])]); ?>
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
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">App Versions</h3>
            <button type="button" wire:click="openModal"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                Add New Version
            </button>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="filterPlatform"
                    class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                    <option value="">All Platforms</option>
                    <option value="ios">iOS</option>
                    <option value="android">Android</option>
                    <option value="windows">Windows</option>
                    <option value="macos">macOS</option>
                    <option value="all">All</option>
                </select>
                <select wire:model.live="filterStatus"
                    class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="deprecated">Deprecated</option>
                </select>
            </div>
            <label class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <iconify-icon icon="material-symbols-light:search" width="18" height="18"></iconify-icon>
                </span>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search version..."
                    class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 sm:w-64">
            </label>
        </div>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">Version</th>
                        <th class="px-3 py-3 font-medium">Platforms</th>
                        <th class="px-3 py-3 font-medium">Min Version</th>
                        <th class="px-3 py-3 font-medium">Release Date</th>
                        <th class="px-3 py-3 text-center font-medium">Priority</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $versions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $version): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr wire:key="app-version-<?php echo e($version->id); ?>" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                            <td class="px-5 py-3">
                                <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90"><?php echo e($version->version); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($version->priority > 0): ?>
                                    <span class="ml-1 rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-400">Priority <?php echo e($version->priority); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex flex-wrap gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $version->getAvailablePlatforms(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $st = $version->getSettingsForPlatform($plt);
                                            $cls = 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400';
                                            if ($st['is_deprecated'] ?? false) {
                                                $cls = 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400';
                                            } elseif ($st['force_update'] ?? false) {
                                                $cls = 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400';
                                            }
                                        ?>
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium <?php echo e($cls); ?>"><?php echo e(strtoupper($plt)); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($version->min_supported_version ?? 'N/A'); ?></td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($version->release_date?->format('Y-m-d') ?? 'N/A'); ?></td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-semibold text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400"><?php echo e($version->priority); ?></span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="edit(<?php echo e($version->id); ?>)" title="Edit"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                        <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="$js.confirmDelete(<?php echo e($version->id); ?>)" title="Delete"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">No versions found</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <?php echo e($versions->links('components.tailadmin.pagination', data: ['scrollTo' => false])); ?>

        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
        <div class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">
            <div wire:click="closeModal" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[8px] dark:bg-gray-900/60"></div>

            <div class="relative max-h-[90vh] w-full max-w-[900px] overflow-y-auto rounded-2xl bg-white p-6 shadow-theme-xl dark:bg-gray-900">
                <button type="button" wire:click="closeModal"
                    class="absolute right-5 top-5 flex h-9 w-9 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/5 dark:hover:text-gray-300">
                    <iconify-icon icon="bx:x" width="20" height="20"></iconify-icon>
                </button>

                <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">
                    <?php echo e($editMode ? 'Edit Version' : 'Add New Version'); ?>

                </h4>

                <form wire:submit.prevent="save" class="space-y-6">
                    
                    <div class="<?php echo e($cardClass); ?>">
                        <div class="px-5 py-4 sm:px-6">
                            <h5 class="text-theme-sm font-semibold text-gray-700 dark:text-gray-300">General Settings</h5>
                        </div>
                        <div class="grid grid-cols-1 gap-4 border-t border-gray-100 p-5 dark:border-gray-800 sm:grid-cols-3 sm:p-6">
                            <div>
                                <label class="<?php echo e($labelClass); ?>">Version *</label>
                                <input type="text" wire:model="version" placeholder="1.0.0" <?php if($editMode): ?> readonly <?php endif; ?> class="<?php echo e($inputClass); ?> <?php if($editMode): ?> opacity-60 <?php endif; ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="<?php echo e($labelClass); ?>">Min Supported Version</label>
                                <input type="text" wire:model="min_supported_version" placeholder="1.0.0" class="<?php echo e($inputClass); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['min_supported_version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="<?php echo e($labelClass); ?>">Priority (0-10)</label>
                                <input type="number" wire:model="priority" min="0" max="10" class="<?php echo e($inputClass); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="<?php echo e($labelClass); ?>">Release Date</label>
                                <input type="datetime-local" wire:model="release_date" class="<?php echo e($inputClass); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['release_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="sm:col-span-3">
                                <label class="<?php echo e($labelClass); ?>">Release Notes</label>
                                <textarea wire:model="release_notes" rows="3" placeholder="What's new in this version..." class="<?php echo e($inputClass); ?> h-auto py-2.5"></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['release_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="sm:col-span-3">
                                <label class="<?php echo e($labelClass); ?>">Update Message</label>
                                <textarea wire:model="update_message" rows="2" placeholder="Message to show users..." class="<?php echo e($inputClass); ?> h-auto py-2.5"></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['update_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="<?php echo e($cardClass); ?>">
                        <div class="px-5 py-4 sm:px-6">
                            <h5 class="text-theme-sm font-semibold text-gray-700 dark:text-gray-300">Platform-Specific Settings</h5>
                            <p class="text-theme-xs text-gray-400">Enable and configure each platform individually</p>
                        </div>
                        <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                            <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-4 dark:border-gray-800">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $platformNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkey => $pname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" wire:click="$set('activeTab', '<?php echo e($pkey); ?>')"
                                        class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-theme-sm font-medium transition-colors <?php echo e($activeTab === $pkey ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400' : 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5'); ?>">
                                        <?php echo e($pname); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platforms[$pkey]['available']): ?>
                                            <iconify-icon icon="mdi:check-circle" width="14" height="14" class="text-success-500"></iconify-icon>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $platformNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkey => $pname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === $pkey): ?>
                                    <div class="mt-4 space-y-4">
                                        <?php if (isset($component)) { $__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.toggle','data' => ['wireModel' => 'platforms.'.e($pkey).'.available','label' => 'Enable '.e($pname).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-model' => 'platforms.'.e($pkey).'.available','label' => 'Enable '.e($pname).'']); ?>
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

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($platforms[$pkey]['available']): ?>
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label class="<?php echo e($labelClass); ?>">Build Number</label>
                                                    <input type="text" wire:model="platforms.<?php echo e($pkey); ?>.build_number" placeholder="100" class="<?php echo e($inputClass); ?>">
                                                </div>
                                                <div>
                                                    <label class="<?php echo e($labelClass); ?>">Deprecation Date</label>
                                                    <input type="datetime-local" wire:model="platforms.<?php echo e($pkey); ?>.deprecation_date" class="<?php echo e($inputClass); ?>">
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label class="<?php echo e($labelClass); ?>">Download URL</label>
                                                    <input type="url" wire:model="platforms.<?php echo e($pkey); ?>.download_url" placeholder="https://apps.apple.com/..." class="<?php echo e($inputClass); ?>">
                                                </div>
                                                <div class="flex flex-wrap gap-x-6 gap-y-3 sm:col-span-2">
                                                    <?php if (isset($component)) { $__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.toggle','data' => ['wireModel' => 'platforms.'.e($pkey).'.force_update','label' => 'Force Update']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-model' => 'platforms.'.e($pkey).'.force_update','label' => 'Force Update']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.toggle','data' => ['wireModel' => 'platforms.'.e($pkey).'.is_deprecated','label' => 'Mark as Deprecated']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-model' => 'platforms.'.e($pkey).'.is_deprecated','label' => 'Mark as Deprecated']); ?>
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
                                            </div>
                                        <?php else: ?>
                                            <div class="rounded-lg border border-blue-light-200 bg-blue-light-50 px-4 py-3 text-theme-sm text-blue-light-700 dark:border-blue-light-500/30 dark:bg-blue-light-500/10 dark:text-blue-light-400">
                                                Enable this platform to configure its settings.
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </form>

                <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                    <button type="button" wire:click="closeModal"
                        class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                        Cancel
                    </button>
                    <button type="button" wire:click="save"
                        class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                        <?php echo e($editMode ? 'Update Version' : 'Create Version'); ?>

                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
    <?php
        $__scriptKey = '3607350335-0';
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
                    $wire.delete(id);
                }
            });
        });

        $wire.on('sweetAlert', (event) => {
            Swal.fire({
                title: event.message,
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
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/app-version-management.blade.php ENDPATH**/ ?>