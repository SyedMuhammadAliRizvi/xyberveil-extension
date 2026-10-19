<?php $__env->startSection('title', 'Manage User'); ?>
<div x-data="{ tab: '<?php echo e($activeTab); ?>' }">
    <?php if (isset($component)) { $__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [
        ['label' => 'Users', 'route' => 'users.all'],
        ['label' => 'Manage'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Users', 'route' => 'users.all'],
        ['label' => 'Manage'],
    ])]); ?>
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
        $rowClass = 'flex items-start justify-between gap-3 border-b border-gray-100 py-2.5 text-theme-sm last:border-b-0 dark:border-gray-800';
        $labelClass = 'shrink-0 text-gray-400';
        $valueClass = 'text-right font-medium text-gray-700 dark:text-gray-300';
        $actionBtnClass = 'flex items-center gap-2 rounded-lg border px-4 py-2.5 text-theme-sm font-medium transition-colors';
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->hasProxyAccess()): ?>
        
        <div class="mb-5 flex gap-1 border-b border-gray-200 dark:border-gray-800">
            <button type="button" @click="tab = 'vpn'" wire:click="$set('activeTab', 'vpn')"
                class="flex items-center gap-1.5 border-b-2 px-4 py-2.5 text-theme-sm font-medium transition-colors"
                :class="tab === 'vpn' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'">
                <iconify-icon icon="mdi:shield-account" width="16" height="16"></iconify-icon>
                VPN Account
            </button>
            <button type="button" @click="tab = 'proxy'" wire:click="$set('activeTab', 'proxy')"
                class="flex items-center gap-1.5 border-b-2 px-4 py-2.5 text-theme-sm font-medium transition-colors"
                :class="tab === 'proxy' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'">
                <iconify-icon icon="mdi:server-network" width="16" height="16"></iconify-icon>
                Proxy
            </button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div x-show="tab === 'vpn'">
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        
        <div class="<?php echo e($cardClass); ?>">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Personal Info</h3>
            </div>
            <div class="border-t border-gray-100 px-5 py-2 dark:border-gray-800 sm:px-6">
                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Full Name</span>
                    <span class="<?php echo e($valueClass); ?>"><?php echo e($user->name); ?></span>
                </div>
                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Email</span>
                    <span class="<?php echo e($valueClass); ?>"><?php echo e($user->email); ?></span>
                </div>
                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Role</span>
                    <span class="<?php echo e($valueClass); ?>"><?php echo e(Str::title($user->role)); ?></span>
                </div>
                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Last Login</span>
                    <span class="<?php echo e($valueClass); ?>"><?php echo e($user->last_login ? $user->last_login->diffForHumans() : 'Never'); ?></span>
                </div>
                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Proxy Status</span>
                    <span class="<?php echo e($valueClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proxySession): ?>
                            <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                Connected to <?php echo e($proxySession['server_flag'] ?? ''); ?> <?php echo e($proxySession['server_name'] ?? 'Unknown server'); ?>

                            </span>
                            <div class="mt-1 text-theme-xs text-gray-400">since <?php echo e(\Illuminate\Support\Carbon::parse($proxySession['started_at'])->diffForHumans()); ?></div>
                        <?php else: ?>
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Not connected</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                </div>
                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Email Verified</span>
                    <span class="<?php echo e($valueClass); ?>"><?php echo e($user->hasVerifiedEmail() ? $user->email_verified_at->toDayDateTimeString() : 'No'); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isBanned()): ?>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Banned At</span>
                        <span class="<?php echo e($valueClass); ?>"><?php echo e($user->banned_at->toDayDateTimeString()); ?></span>
                    </div>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Ban Reason</span>
                        <span class="<?php echo e($valueClass); ?>"><?php echo e($user->ban_reason); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Registered</span>
                    <span class="<?php echo e($valueClass); ?>"><?php echo e($user->created_at->toDayDateTimeString()); ?></span>
                </div>
            </div>
        </div>

        
        <div class="<?php echo e($cardClass); ?>">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Trial Information</h3>
            </div>
            <div class="border-t border-gray-100 px-5 py-2 dark:border-gray-800 sm:px-6">
                <?php $trialDetails = $user->getTrialStatusWithDescription(); ?>

                <div class="<?php echo e($rowClass); ?>">
                    <span class="<?php echo e($labelClass); ?>">Status</span>
                    <span class="<?php echo e($valueClass); ?>">
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($trialDetails['status'] == 'active' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : ($trialDetails['status'] == 'expired' ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' : 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400')); ?>">
                            <?php echo e($trialDetails['description']); ?>

                        </span>
                    </span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->hasUsedTrial()): ?>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Started At</span>
                        <span class="<?php echo e($valueClass); ?>"><?php echo e($user->trial_started_at ? $user->trial_started_at->toDayDateTimeString() : 'N/A'); ?></span>
                    </div>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Ends At</span>
                        <span class="<?php echo e($valueClass); ?>"><?php echo e($user->trial_ends_at ? $user->trial_ends_at->toDayDateTimeString() : 'N/A'); ?></span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isOnTrial()): ?>
                        <div class="<?php echo e($rowClass); ?>">
                            <span class="<?php echo e($labelClass); ?>">Days Remaining</span>
                            <span class="<?php echo e($valueClass); ?> font-bold text-warning-600 dark:text-warning-400"><?php echo e($trialDetails['days_remaining']); ?> days</span>
                        </div>
                        <div class="py-2.5 text-theme-sm">
                            <span class="<?php echo e($labelClass); ?> mb-1.5 block">Progress</span>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-white/10">
                                <div class="h-full rounded-full bg-blue-light-500" style="width: <?php echo e($trialDetails['progress_percentage']); ?>%"></div>
                            </div>
                            <div class="mt-1 text-theme-xs text-gray-400"><?php echo e(number_format($trialDetails['progress_percentage'], 1)); ?>% completed</div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Available</span>
                        <span class="<?php echo e($valueClass); ?> font-bold text-success-600 dark:text-success-400">&check; User can start trial</span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role == 'user'): ?>
            
            <div class="<?php echo e($cardClass); ?>">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Manage Plan</h3>
                </div>
                <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->activeSubscription): ?>
                        <div class="<?php echo e($rowClass); ?>">
                            <span class="<?php echo e($labelClass); ?>">Plan</span>
                            <span class="<?php echo e($valueClass); ?>"><?php echo e($user->activeSubscription->plan->name); ?></span>
                        </div>
                        <div class="<?php echo e($rowClass); ?>">
                            <span class="<?php echo e($labelClass); ?>">Expires At</span>
                            <span class="<?php echo e($valueClass); ?>"><?php echo e($user->activeSubscription->ends_at->toDayDateTimeString()); ?></span>
                        </div>
                        <button type="button" wire:click="$js.confirmCancelPlan()"
                            class="<?php echo e($actionBtnClass); ?> mt-4 border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            Cancel Plan
                        </button>
                    <?php else: ?>
                        <h6 class="mb-3 text-theme-sm font-semibold text-gray-700 dark:text-gray-300">Add Plan</h6>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                            <?php if (isset($component)) { $__componentOriginal784435fc350028aa1cabbd56f101a709 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal784435fc350028aa1cabbd56f101a709 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.alert','data' => ['type' => 'warning','message' => ''.e($errors->first()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'warning','message' => ''.e($errors->first()).'']); ?>
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
                        <select wire:model="selectedPlan"
                            class="mb-4 h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90">
                            <option value="" selected>Select Plan</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($plan->id); ?>"><?php echo e($plan->name); ?> - $<?php echo e($plan->discount_price ?? $plan->original_price); ?> / <?php echo e($plan->invoice_period); ?> - <?php echo e($plan->invoice_interval); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <button type="button" wire:click="$js.confirmAddPlan()"
                            class="<?php echo e($actionBtnClass); ?> border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            <?php echo e($user->activePlan ? 'Extend Plan' : 'Add Plan'); ?>

                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="<?php echo e($cardClass); ?>">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Payment History</h3>
                </div>
                <div class="divide-y divide-gray-100 border-t border-gray-100 dark:divide-gray-800 dark:border-gray-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $user->localSubscriptions->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 sm:px-6">
                            <div>
                                <div class="text-theme-sm font-semibold text-gray-800 dark:text-white/90"><?php echo e($purchase->plan->name); ?></div>
                                <p class="mt-0.5 text-theme-xs text-gray-400">
                                    (<?php echo e($purchase->starts_at->toFormattedDateString()); ?> - <?php echo e($purchase->ends_at->toFormattedDateString()); ?>) - <?php echo e(Str::title($purchase->status)); ?>

                                </p>
                            </div>
                            <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">$<?php echo e($purchase->amount_paid); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="px-5 py-6 text-theme-sm text-gray-400 sm:px-6">No purchase history available.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="<?php echo e($cardClass); ?>">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">User Actions</h3>
            </div>
            <div class="flex flex-col gap-2 border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $user->hasUsedTrial()): ?>
                    <button type="button" wire:click="$js.confirmGrantTrial()"
                        class="<?php echo e($actionBtnClass); ?> border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                        <iconify-icon icon="bx:gift" width="18" height="18"></iconify-icon>
                        Grant Trial (7 days)
                    </button>
                <?php else: ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isOnTrial()): ?>
                        <button type="button" wire:click="$js.confirmExtendTrial()"
                            class="<?php echo e($actionBtnClass); ?> border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            <iconify-icon icon="bx:time" width="18" height="18"></iconify-icon>
                            Extend Trial
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <button type="button" wire:click="$js.confirmResetTrial()"
                        class="<?php echo e($actionBtnClass); ?> border-warning-200 text-warning-600 hover:bg-warning-50 dark:border-warning-500/30 dark:text-warning-400 dark:hover:bg-warning-500/10">
                        <iconify-icon icon="bx:refresh" width="18" height="18"></iconify-icon>
                        Reset Trial
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $user->hasVerifiedEmail()): ?>
                    <button type="button" wire:click="verifyEmailManually"
                        class="<?php echo e($actionBtnClass); ?> border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                        <iconify-icon icon="bx:check-circle" width="18" height="18"></iconify-icon>
                        Verify Email Manually
                    </button>

                    <button type="button" wire:click="resendVerificationEmail"
                        class="<?php echo e($actionBtnClass); ?> border-brand-200 text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="bx:mail-send" width="18" height="18"></iconify-icon>
                        Resend Verification Email
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <button type="button" wire:click="sendPasswordReset"
                    class="<?php echo e($actionBtnClass); ?> border-brand-200 text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                    <iconify-icon icon="bx:key" width="18" height="18"></iconify-icon>
                    Send Password Reset Email
                </button>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isBanned()): ?>
                    <button type="button" wire:click="unbanUser"
                        class="<?php echo e($actionBtnClass); ?> border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                        <iconify-icon icon="bx:user-check" width="18" height="18"></iconify-icon>
                        Unban User
                    </button>
                <?php else: ?>
                    <button type="button" wire:click="$js.confirmBanUser()"
                        class="<?php echo e($actionBtnClass); ?> border-error-200 text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                        <iconify-icon icon="bx:user-x" width="18" height="18"></iconify-icon>
                        Ban User
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <button type="button" wire:click="$js.confirmDelete()"
                    class="<?php echo e($actionBtnClass); ?> border-error-200 text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                    <iconify-icon icon="bx:trash" width="18" height="18"></iconify-icon>
                    Delete User
                </button>
            </div>
        </div>
    </div>

    
    <div class="<?php echo e($cardClass); ?> mt-6">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="flex items-center gap-2 text-base font-semibold text-gray-800 dark:text-white/90">
                <iconify-icon icon="mdi:devices" width="20" height="20"></iconify-icon>
                Devices
                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">
                    <?php echo e($user->hasProxyAccess() ? $user->devices->count() + $user->proxyExtensionDevices->count() : $user->devices->count()); ?>

                </span>
                <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-semibold text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400" title="Effective device limit">
                    Limit: <?php echo e($user->deviceLimit()); ?>

                </span>
            </h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->devices->count() > 0): ?>
                <button type="button" wire:click="$js.confirmRevokeAllDevices()"
                    class="flex items-center gap-1.5 rounded-lg border border-error-200 px-3 py-2 text-theme-xs font-medium text-error-600 transition-colors hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                    <iconify-icon icon="mdi:key-remove" width="16" height="16"></iconify-icon>
                    Revoke All Sessions
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
            <div class="mb-4">
                <label class="mb-1.5 block text-theme-sm font-medium text-gray-600 dark:text-gray-400">
                    Custom Device Limit Override <span class="text-gray-400">(leave empty to use plan/default)</span>
                </label>
                <div class="flex items-center gap-2">
                    <input type="number" min="1" max="255" wire:model.defer="customDeviceLimit" placeholder="e.g. 3"
                        class="h-10 w-32 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90">
                    <button type="button" wire:click="setCustomDeviceLimit"
                        class="flex h-10 items-center rounded-lg border border-brand-200 px-3 text-theme-sm font-medium text-brand-600 transition-colors hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        Apply
                    </button>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->hasProxyAccess() ? ($user->devices->isEmpty() && $user->proxyExtensionDevices->isEmpty()) : $user->devices->isEmpty()): ?>
                <p class="text-theme-sm text-gray-400">No devices registered for this user.</p>
            <?php else: ?>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                                <th class="px-3 py-3 font-medium">Device</th>
                                <th class="px-3 py-3 font-medium">Platform / OS</th>
                                <th class="px-3 py-3 font-medium"><?php echo e($user->hasProxyAccess() ? 'Kind' : 'Type'); ?></th>
                                <th class="px-3 py-3 font-medium"><?php echo e($user->hasProxyAccess() ? 'IP / Key' : 'IP Address'); ?></th>
                                <th class="px-3 py-3 font-medium">Location</th>
                                <th class="px-3 py-3 font-medium">Timezone</th>
                                <th class="px-3 py-3 font-medium">Last Seen</th>
                                <th class="px-3 py-3 text-center font-medium">Status</th>
                                <th class="px-3 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $user->devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr wire:key="vpn-device-<?php echo e($device->id); ?>" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2">
                                            <iconify-icon icon="<?php echo e(match ($device->platform) {
                                                'android' => 'mdi:android',
                                                'ios' => 'mdi:apple-ios',
                                                'macos' => 'mdi:apple',
                                                'windows' => 'mdi:microsoft-windows',
                                                'web' => 'mdi:web',
                                                default => 'mdi:devices',
                                            }); ?>" width="20" height="20" class="shrink-0 text-gray-400"></iconify-icon>
                                            <div>
                                                <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($device->name ?: 'Unnamed Device'); ?></div>
                                                <div class="text-theme-xs text-gray-400"><?php echo e($device->model ?: '—'); ?></div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->browser): ?>
                                                    <div class="text-theme-xs text-gray-400"><?php echo e($device->browser); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                        <div><?php echo e(Str::title($device->platform ?? '—')); ?></div>
                                        <div class="text-theme-xs text-gray-400"><?php echo e($device->os ?: '—'); ?></div>
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->hasProxyAccess()): ?>
                                            <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">VPN App</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->device_type): ?>
                                                <div class="mt-0.5 text-theme-xs text-gray-400"><?php echo e(Str::title($device->device_type)); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->app_version): ?>
                                                <div class="text-theme-xs text-gray-400">v<?php echo e($device->app_version); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <?php echo e($device->device_type ? Str::title($device->device_type) : '—'); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm">
                                        <?php $ipBlocked = $device->ip_address && isset($blockedIpSet[$device->ip_address]); ?>
                                        <code class="text-theme-xs text-gray-500 dark:text-gray-400"><?php echo e($device->ip_address ?: '—'); ?></code>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ipBlocked): ?>
                                            <span class="ml-1 rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-400">IP Blocked</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $user->hasProxyAccess() && $device->app_version): ?>
                                            <div class="text-theme-xs text-gray-400">v<?php echo e($device->app_version); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                        <?php echo e(collect([$device->city, $device->country])->filter()->implode(', ') ?: '—'); ?>

                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($device->timezone ?: '—'); ?></td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($device->last_seen_at ? $device->last_seen_at->diffForHumans() : 'Never'); ?></td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->is_blocked): ?>
                                            <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-semibold text-error-600 dark:bg-error-500/15 dark:text-error-400">Blocked</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->blocked_reason): ?>
                                                <div class="mt-0.5 text-theme-xs text-gray-400"><?php echo e($device->blocked_reason); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php elseif($device->isRevoked()): ?>
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Revoked</span>
                                        <?php else: ?>
                                            <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Active</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-1.5">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->is_blocked): ?>
                                                <button type="button" wire:click="unblockDevice(<?php echo e($device->id); ?>)" title="Unblock"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                                    <iconify-icon icon="mdi:lock-open-variant-outline" width="16" height="16"></iconify-icon>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" wire:click="$js.confirmBlockDevice(<?php echo e($device->id); ?>)" title="Block Device"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                                    <iconify-icon icon="mdi:block-helper" width="16" height="16"></iconify-icon>
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $device->isRevoked()): ?>
                                                <button type="button" wire:click="$js.confirmRevokeDevice(<?php echo e($device->id); ?>)" title="Revoke Access"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">
                                                    <iconify-icon icon="mdi:key-remove" width="16" height="16"></iconify-icon>
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->ip_address): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ipBlocked): ?>
                                                    <button type="button" wire:click="$js.confirmUnblockDeviceIp('<?php echo e($device->ip_address); ?>')" title="Unblock IP"
                                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                                        <iconify-icon icon="mdi:ip-network-outline" width="16" height="16"></iconify-icon>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" wire:click="$js.confirmBlockDeviceIp(<?php echo e($device->id); ?>, '<?php echo e($device->ip_address); ?>')" title="Block IP"
                                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                                        <iconify-icon icon="mdi:ip-network-outline" width="16" height="16"></iconify-icon>
                                                    </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <button type="button" wire:click="$js.confirmRemoveDevice(<?php echo e($device->id); ?>)" title="Remove Device"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                                <iconify-icon icon="mingcute:delete-2-line" width="16" height="16"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->hasProxyAccess()): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $user->proxyExtensionDevices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr wire:key="proxy-device-<?php echo e($device->id); ?>" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/5">
                                        <td class="px-3 py-3">
                                            <div class="flex items-center gap-2">
                                                <iconify-icon icon="mdi:web" width="20" height="20" class="shrink-0 text-gray-400"></iconify-icon>
                                                <div>
                                                    <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($device->browser_name ?: 'Browser'); ?></div>
                                                    <div class="text-theme-xs text-gray-400"><?php echo e($device->browser_version ?: '—'); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                            <div><?php echo e($device->os_name ?: '—'); ?></div>
                                            <div class="text-theme-xs text-gray-400"><?php echo e($device->os_version ?: ''); ?></div>
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                            <span class="rounded-full bg-blue-light-50 px-2 py-0.5 text-xs font-medium text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">Extension</span>
                                            <div class="mt-0.5 text-theme-xs text-gray-400">v<?php echo e($device->extension_version ?: '—'); ?></div>
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->ip_address): ?>
                                                <code class="text-theme-xs text-gray-500 dark:text-gray-400"><?php echo e($device->ip_address); ?></code>
                                                <div class="text-theme-xs text-gray-400"><code><?php echo e(Str::limit($device->device_key, 16)); ?></code></div>
                                            <?php else: ?>
                                                <code class="text-theme-xs text-gray-400"><?php echo e(Str::limit($device->device_key, 20)); ?></code>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e(collect([$device->city, $device->country])->filter()->implode(', ') ?: '—'); ?></td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($device->timezone ?: '—'); ?></td>
                                        <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($device->last_seen?->diffForHumans() ?? 'Never'); ?></td>
                                        <td class="px-3 py-3 text-center">
                                            <label class="inline-flex cursor-pointer items-center gap-2">
                                                <span class="relative inline-flex h-6 w-11 shrink-0 items-center">
                                                    <input type="checkbox" <?php if($device->is_allowed): echo 'checked'; endif; ?>
                                                        wire:change="setExtensionDeviceAllowed(<?php echo e($device->id); ?>, $event.target.checked)"
                                                        class="peer sr-only">
                                                    <span class="absolute inset-0 rounded-full bg-gray-200 transition-colors peer-checked:bg-brand-500 dark:bg-white/10"></span>
                                                    <span class="absolute left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                                                </span>
                                                <span class="text-theme-xs text-gray-500 dark:text-gray-400"><?php echo e($device->is_allowed ? 'Allowed' : 'Blocked'); ?></span>
                                            </label>
                                        </td>
                                        <td class="px-3 py-3">
                                            <button type="button" wire:click="$js.confirmRemoveProxyExtensionDevice(<?php echo e($device->id); ?>)" title="Remove extension device and force logout"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                                <iconify-icon icon="mingcute:delete-2-line" width="16" height="16"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->hasProxyAccess()): ?>
    
    <div x-show="tab === 'proxy'" x-cloak>
        <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
            
            <div wire:key="proxy-account-card" class="<?php echo e($cardClass); ?>">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Proxy Account</h3>
                </div>
                <div class="border-t border-gray-100 px-5 py-2 dark:border-gray-800 sm:px-6">
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Member ID</span>
                        <span class="<?php echo e($valueClass); ?>"><code><?php echo e($this->memberId); ?></code></span>
                    </div>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Linux Username</span>
                        <span class="<?php echo e($valueClass); ?>"><code><?php echo e($user->linux_username); ?></code></span>
                    </div>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Proxy Account</span>
                        <span class="<?php echo e($valueClass); ?>">
                            <?php
                                $proxyBadgeClasses = [
                                    'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                    'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                    'error' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                                ];
                            ?>
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($proxyBadgeClasses[$statusColors[(string) ($proxyStatus ?? '')] ?? ''] ?? 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400'); ?>">
                                <?php echo e(ucfirst($proxyStatus ?? 'none')); ?>

                            </span>
                        </span>
                    </div>
                    <div class="<?php echo e($rowClass); ?>">
                        <span class="<?php echo e($labelClass); ?>">Connection</span>
                        <span class="<?php echo e($valueClass); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proxySession): ?>
                                <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                    Connected to <?php echo e($proxySession['server_flag'] ?? ''); ?> <?php echo e($proxySession['server_name'] ?? 'Unknown server'); ?>

                                </span>
                                <div class="mt-1 text-theme-xs text-gray-400">since <?php echo e(\Illuminate\Support\Carbon::parse($proxySession['started_at'])->diffForHumans()); ?></div>
                            <?php else: ?>
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Not connected</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            
            <div wire:key="proxy-actions-card" class="<?php echo e($cardClass); ?>">
                <div class="px-5 py-4 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Proxy Actions</h3>
                </div>
                <div class="flex flex-col gap-2 border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proxyStatus === 'pending'): ?>
                        <button type="button" wire:click="approveProxy"
                            class="<?php echo e($actionBtnClass); ?> border-success-200 text-success-600 hover:bg-success-50 dark:border-success-500/30 dark:text-success-400 dark:hover:bg-success-500/10">
                            <iconify-icon icon="bx:check" width="18" height="18"></iconify-icon>
                            Approve
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button type="button" wire:click="$js.confirmResetProxyPassword()"
                        class="<?php echo e($actionBtnClass); ?> border-brand-200 text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                        <iconify-icon icon="bx:key" width="18" height="18"></iconify-icon>
                        Reset Proxy Password
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proxyStatus === 'active'): ?>
                        <button type="button" wire:click="$js.confirmBanProxy()"
                            class="<?php echo e($actionBtnClass); ?> border-error-200 text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">
                            <iconify-icon icon="bx:block" width="18" height="18"></iconify-icon>
                            Ban Proxy Access
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proxyStatus === 'banned'): ?>
                        <button type="button" wire:click="unbanProxy"
                            class="<?php echo e($actionBtnClass); ?> border-blue-light-200 text-blue-light-600 hover:bg-blue-light-50 dark:border-blue-light-500/30 dark:text-blue-light-400 dark:hover:bg-blue-light-500/10">
                            <iconify-icon icon="bx:user-check" width="18" height="18"></iconify-icon>
                            Unban Proxy Access
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div wire:key="connection-history-card" class="<?php echo e($cardClass); ?>">
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                <h3 class="flex items-center gap-2 text-base font-semibold text-gray-800 dark:text-white/90">
                    <iconify-icon icon="mdi:connection" width="20" height="20"></iconify-icon>
                    Connection History
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400"><?php echo e($connectionHistory?->total() ?? 0); ?></span>
                </h3>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($connectionHistory && $connectionHistory->isNotEmpty()): ?>
                    <select wire:key="sessions-per-page-select" wire:model.live="sessionsPerPage"
                        class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-700 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-gray-300">
                        <option value="5">5 per page</option>
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $connectionHistory || $connectionHistory->isEmpty()): ?>
                <p class="border-t border-gray-100 px-5 py-6 text-theme-sm text-gray-400 dark:border-gray-800 sm:px-6">No connection sessions recorded.</p>
            <?php else: ?>
                <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                                <th class="px-5 py-3 font-medium">Server</th>
                                <th class="px-3 py-3 font-medium">Client IP</th>
                                <th class="px-3 py-3 font-medium">Started</th>
                                <th class="px-3 py-3 font-medium">Duration</th>
                                <th class="px-3 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $connectionHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr wire:key="proxy-session-<?php echo e($session->id); ?>">
                                    <td class="px-5 py-3 text-theme-sm text-gray-700 dark:text-gray-300"><?php if (isset($component)) { $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.flag','data' => ['flag' => $session->server?->flag]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.flag'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flag' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($session->server?->flag)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $attributes = $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $component = $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?> <?php echo e($session->server?->name ?? '—'); ?></td>
                                    <td class="px-3 py-3"><code class="text-theme-xs text-gray-500 dark:text-gray-400"><?php echo e($session->client_ip ?? '—'); ?></code></td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($session->started_at?->diffForHumans()); ?></td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400">
                                        <?php
                                            $secs = $session->duration_seconds
                                                ?? ($session->isActive() ? (int) $session->started_at?->diffInSeconds(now()) : 0);
                                            $h = intdiv($secs, 3600);
                                            $m = intdiv($secs % 3600, 60);
                                            $s = $secs % 60;
                                        ?>
                                        <?php echo e(sprintf('%02d:%02d:%02d', $h, $m, $s)); ?>

                                    </td>
                                    <td class="px-3 py-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($session->isActive()): ?>
                                            <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Live</span>
                                        <?php else: ?>
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-white/5 dark:text-gray-400">Ended</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                    <?php echo e($connectionHistory->links('components.tailadmin.pagination', data: ['scrollTo' => false])); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
    <?php
        $__scriptKey = '1497777146-0';
        ob_start();
    ?>
    <script>
        $js('confirmCancelPlan', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to cancel this plan?',
                icon: 'warning',
                width: 500,
                html: `
                    <div style="margin-bottom: 20px; text-align: left;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Cancellation Reason:</label>
                        <input type="text" id="cancelReason" placeholder="Enter cancellation reason"
                               style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;
                                      font-size: 14px; background: white; color: #333; box-sizing: border-box;">
                    </div>
                    <div style="margin-top: 15px; text-align: left; display: flex; align-items: center;">
                        <input type="checkbox" id="endImmediately"
                               style="width: 18px; height: 18px; margin-right: 10px; cursor: pointer;
                                      accent-color: #dc3545;">
                        <label for="endImmediately" style="font-weight: 500; color: #dc3545; cursor: pointer; margin: 0;">
                            End subscription immediately
                        </label>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it',
                customClass: {
                    htmlContainer: 'swal2-html-container'
                },
                preConfirm: () => {
                    const reason = document.getElementById('cancelReason').value;
                    const endImmediately = document.getElementById('endImmediately').checked;

                    if (!reason.trim()) {
                        Swal.showValidationMessage('Cancellation reason is required');
                        return false;
                    }

                    return {
                        reason: reason.trim(),
                        endImmediately
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.cancelSubscription(result.value.reason, result.value.endImmediately);
                }
            });
        });

        $js('confirmAddPlan', () => {
            Swal.fire({
                title: 'Confirm Plan Change?',
                text: 'Are you sure you want to add or extend this plan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.addPlan();
                }
            });
        });

        $js('confirmBanUser', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will prevent the user from accessing the system.',
                icon: 'warning',
                input: 'text',
                inputLabel: 'Reason for banning',
                inputPlaceholder: 'Enter reason for banning',
                showCancelButton: true,
                confirmButtonText: 'Yes, ban user!',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Reason is required');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.banUser(result.value);
                }
            });
        });

        $js('confirmDelete', () => {
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
                    $wire.deleteUser();
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

        // Trial Management Functions
        $js('confirmGrantTrial', () => {
            Swal.fire({
                title: 'Grant Trial',
                text: 'How many days should the trial last?',
                icon: 'question',
                input: 'number',
                inputValue: 7,
                inputAttributes: {
                    min: 1,
                    max: 30,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonText: 'Grant Trial',
                preConfirm: (days) => {
                    if (!days || days < 1 || days > 30) {
                        Swal.showValidationMessage('Please enter a valid number of days (1-30)');
                    }
                    return days;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.grantTrial(parseInt(result.value));
                }
            });
        });

        $js('confirmExtendTrial', () => {
            Swal.fire({
                title: 'Extend Trial',
                text: 'How many additional days?',
                icon: 'question',
                input: 'number',
                inputValue: 7,
                inputAttributes: {
                    min: 1,
                    max: 30,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonText: 'Extend Trial',
                preConfirm: (days) => {
                    if (!days || days < 1 || days > 30) {
                        Swal.showValidationMessage('Please enter a valid number of days (1-30)');
                    }
                    return days;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.extendTrial(parseInt(result.value));
                }
            });
        });

        $js('confirmResetTrial', () => {
            Swal.fire({
                title: 'Reset Trial',
                text: 'This will allow the user to start a new trial. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f0ad4e',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset trial!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.resetTrial();
                }
            });
        });

        // Device Management
        $js('confirmBlockDevice', (id) => {
            Swal.fire({
                title: 'Block Device?',
                text: 'The session will be terminated and the device will be blocked.',
                icon: 'warning',
                input: 'text',
                inputLabel: 'Reason (optional)',
                inputPlaceholder: 'Enter reason for blocking',
                showCancelButton: true,
                confirmButtonColor: '#f0ad4e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, block it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.blockDevice(id, result.value || '');
                }
            });
        });

        $js('confirmRevokeDevice', (id) => {
            Swal.fire({
                title: 'Revoke Device Access?',
                text: 'The device session will be terminated immediately.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, revoke!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.revokeDevice(id);
                }
            });
        });

        $js('confirmUnblockDeviceIp', (ip) => {
            Swal.fire({
                title: `Unblock IP: ${ip}?`,
                text: 'This IP will be allowed to make API requests again.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, unblock!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.unblockDeviceIp(ip);
                }
            });
        });

        $js('confirmBlockDeviceIp', (id, ip) => {
            Swal.fire({
                title: `Block IP: ${ip}?`,
                text: 'This IP will be blocked from making any API requests.',
                icon: 'warning',
                input: 'text',
                inputLabel: 'Reason (optional)',
                inputPlaceholder: 'Enter reason for blocking',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, block IP!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.blockDeviceIp(id, result.value || '');
                }
            });
        });

        $js('confirmRemoveDevice', (id) => {
            Swal.fire({
                title: 'Remove Device?',
                text: 'The device record will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.removeDevice(id);
                }
            });
        });

        $js('confirmRemoveProxyExtensionDevice', (deviceId) => {
            Swal.fire({
                title: 'Remove this extension device?',
                text: 'The browser will disconnect and log out immediately.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Remove & logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.removeProxyExtensionDevice(deviceId);
                }
            });
        });

        $js('confirmRevokeAllDevices', () => {
            Swal.fire({
                title: 'Revoke All Sessions?',
                text: 'All device sessions for this user will be terminated immediately.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, revoke all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.revokeAllDevices();
                }
            });
        });

        $js('confirmResetProxyPassword', () => {
            Swal.fire({
                title: 'Reset proxy password?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Reset'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.resetProxyPassword();
                }
            });
        });

        $js('confirmBanProxy', () => {
            Swal.fire({
                title: 'Ban proxy access?',
                input: 'text',
                inputLabel: 'Reason (optional)',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ban'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.banProxy(result.value || null);
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
    </script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/manage-user.blade.php ENDPATH**/ ?>