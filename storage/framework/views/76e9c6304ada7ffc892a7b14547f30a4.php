<?php $__env->startSection('title', 'App Maintenance Mode'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [['label' => 'App Maintenance Mode']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'App Maintenance Mode']])]); ?>
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
        $checkboxClass = 'h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-white/5';
    ?>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        
        <div class="space-y-6 xl:col-span-1">
            
            <div class="<?php echo e($cardClass); ?> p-6 text-center">
                <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white/90">Maintenance Status</h3>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($is_enabled && $maintenanceStatus): ?>
                    <div class="rounded-xl border border-error-200 bg-error-50 px-4 py-5 dark:border-error-500/30 dark:bg-error-500/10">
                        <iconify-icon icon="bx:error-circle" width="36" height="36" class="text-error-500"></iconify-icon>
                        <h4 class="mt-2 text-theme-sm font-bold text-error-600 dark:text-error-400">MAINTENANCE MODE ACTIVE</h4>
                        <p class="mt-1 text-theme-xs text-error-500 dark:text-error-400">App is currently in maintenance mode</p>
                    </div>
                <?php else: ?>
                    <div class="rounded-xl border border-success-200 bg-success-50 px-4 py-5 dark:border-success-500/30 dark:bg-success-500/10">
                        <iconify-icon icon="bx:check-circle" width="36" height="36" class="text-success-500"></iconify-icon>
                        <h4 class="mt-2 text-theme-sm font-bold text-success-600 dark:text-success-400">NORMAL OPERATION</h4>
                        <p class="mt-1 text-theme-xs text-success-500 dark:text-success-400">App is operating normally</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <button type="button" wire:click="toggleMaintenance"
                    class="mt-4 flex w-full items-center justify-center gap-1.5 rounded-lg px-4 py-2.5 text-theme-sm font-medium text-white <?php echo e($is_enabled ? 'bg-success-500 hover:bg-success-600' : 'bg-error-500 hover:bg-error-600'); ?>">
                    <iconify-icon icon="<?php echo e($is_enabled ? 'bx:check' : 'bx:error'); ?>" width="18" height="18"></iconify-icon>
                    <?php echo e($is_enabled ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode'); ?>

                </button>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($is_enabled): ?>
                    <button type="button" wire:click="disableNow"
                        class="mt-2 flex w-full items-center justify-center gap-1.5 rounded-lg border border-warning-200 px-4 py-2 text-theme-sm font-medium text-warning-600 hover:bg-warning-50 dark:border-warning-500/30 dark:text-warning-400 dark:hover:bg-warning-500/10">
                        <iconify-icon icon="bx:x" width="16" height="16"></iconify-icon>
                        Disable Immediately
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentMaintenance && $currentMaintenance->scheduled_start && $currentMaintenance->scheduled_end): ?>
                    <div class="mt-4 text-left text-theme-xs text-gray-400">
                        <strong class="text-gray-600 dark:text-gray-300">Scheduled:</strong><br>
                        <?php echo e($currentMaintenance->scheduled_start->format('M d, Y H:i')); ?><br>
                        to<br>
                        <?php echo e($currentMaintenance->scheduled_end->format('M d, Y H:i')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="<?php echo e($cardClass); ?> p-6">
                <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white/90">Quick Schedule</h3>
                <div class="flex flex-col gap-2">
                    <button type="button" wire:click="scheduleNow"
                        class="flex items-center gap-1.5 rounded-lg border border-brand-200 px-3 py-2 text-theme-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="bx:time" width="16" height="16"></iconify-icon>
                        Start Now
                    </button>
                    <button type="button" wire:click="schedule1Hour"
                        class="flex items-center gap-1.5 rounded-lg border border-brand-200 px-3 py-2 text-theme-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="bx:time" width="16" height="16"></iconify-icon>
                        In 1 Hour (60min)
                    </button>
                    <button type="button" wire:click="schedule24Hours"
                        class="flex items-center gap-1.5 rounded-lg border border-brand-200 px-3 py-2 text-theme-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="bx:time" width="16" height="16"></iconify-icon>
                        In 24 Hours (120min)
                    </button>
                    <button type="button" wire:click="clearSchedule"
                        class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                        <iconify-icon icon="bx:x" width="16" height="16"></iconify-icon>
                        Clear Schedule
                    </button>
                </div>
            </div>
        </div>

        
        <div class="<?php echo e($cardClass); ?> xl:col-span-2">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Maintenance Settings</h3>
            </div>
            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="<?php echo e($labelClass); ?>">Title *</label>
                        <input type="text" wire:model="title" class="<?php echo e($inputClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="<?php echo e($labelClass); ?>">Message *</label>
                        <textarea wire:model="message" rows="4" placeholder="Message to display to users during maintenance" class="<?php echo e($inputClass); ?> h-auto py-2.5"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
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
                            <label class="<?php echo e($labelClass); ?>">Scheduled Start</label>
                            <input type="datetime-local" wire:model="scheduled_start" class="<?php echo e($inputClass); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['scheduled_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="mt-1 text-theme-xs text-gray-400">Leave empty for immediate maintenance</p>
                        </div>
                        <div>
                            <label class="<?php echo e($labelClass); ?>">Scheduled End</label>
                            <input type="datetime-local" wire:model="scheduled_end" class="<?php echo e($inputClass); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['scheduled_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="mt-1 text-theme-xs text-gray-400">Leave empty for indefinite maintenance</p>
                        </div>
                    </div>

                    <div class="sm:w-1/2 sm:pr-2">
                        <label class="<?php echo e($labelClass); ?>">Estimated Duration (minutes)</label>
                        <input type="number" wire:model="estimated_duration_minutes" min="1" placeholder="60" class="<?php echo e($inputClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estimated_duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="<?php echo e($labelClass); ?>">Affected Platforms</label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $availablePlatforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-2 text-theme-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" wire:model="affected_platforms" value="<?php echo e($key); ?>" class="<?php echo e($checkboxClass); ?>">
                                    <?php echo e($label); ?>

                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <p class="mt-1 text-theme-xs text-gray-400">Leave empty to affect all platforms</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="<?php echo e($labelClass); ?>">Contact Email</label>
                            <input type="email" wire:model="contact_email" placeholder="support@example.com" class="<?php echo e($inputClass); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="<?php echo e($labelClass); ?>">Contact Phone</label>
                            <input type="text" wire:model="contact_phone" placeholder="+1234567890" class="<?php echo e($inputClass); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['contact_phone'];
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
                        <label class="<?php echo e($labelClass); ?>">Allowed IPs (Bypass Maintenance)</label>
                        <textarea wire:model="allowed_ips" rows="3" placeholder="192.168.1.1&#10;10.0.0.1&#10;One IP per line" class="<?php echo e($inputClass); ?> h-auto py-2.5"></textarea>
                        <p class="mt-1 text-theme-xs text-gray-400">IPs that can bypass maintenance mode (one per line)</p>
                    </div>

                    <div class="border-t border-gray-100 pt-4 dark:border-gray-800">
                        <?php if (isset($component)) { $__componentOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636e0d7d7bf210bb05bfb6eacbe1965d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.toggle','data' => ['wireModel' => 'allow_existing_connections','label' => 'Allow Existing VPN Connections']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-model' => 'allow_existing_connections','label' => 'Allow Existing VPN Connections']); ?>
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
                        <p class="mt-1 text-theme-xs text-gray-400">If enabled, users already connected to VPN can continue using the service</p>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                        <button type="button" wire:click="loadMaintenance"
                            class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                            Reset
                        </button>
                        <button type="submit"
                            class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                            <iconify-icon icon="bx:save" width="16" height="16"></iconify-icon>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="<?php echo e($cardClass); ?> mt-6 p-5 sm:p-6">
        <h3 class="mb-3 text-base font-semibold text-gray-800 dark:text-white/90">How Maintenance Mode Works</h3>
        <ul class="list-disc space-y-1.5 pl-5 text-theme-sm text-gray-600 dark:text-gray-400">
            <li><strong class="text-gray-800 dark:text-white/90">Immediate Maintenance:</strong> Leave scheduled start/end empty to activate maintenance immediately</li>
            <li><strong class="text-gray-800 dark:text-white/90">Scheduled Maintenance:</strong> Set future start/end times to schedule maintenance in advance</li>
            <li><strong class="text-gray-800 dark:text-white/90">Platform-Specific:</strong> Select specific platforms to affect, or leave empty for all platforms</li>
            <li><strong class="text-gray-800 dark:text-white/90">IP Whitelist:</strong> Add admin IPs to bypass maintenance mode for testing</li>
            <li><strong class="text-gray-800 dark:text-white/90">Existing Connections:</strong> Enable to allow users already connected to VPN to continue using it during maintenance</li>
            <li><strong class="text-gray-800 dark:text-white/90">API Response:</strong> The mobile app will receive maintenance status via <code>/api/app/version-check</code> endpoint</li>
        </ul>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/app-maintenance-mode.blade.php ENDPATH**/ ?>