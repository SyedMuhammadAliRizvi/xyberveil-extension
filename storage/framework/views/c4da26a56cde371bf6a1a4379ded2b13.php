<?php $__env->startSection('title', 'Proxy Servers'); ?>
<div <?php if($this->hasInstalling()): ?> wire:poll.5000ms="loadServers" <?php endif; ?>>

    <?php if (isset($component)) { $__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [['label' => 'Proxy Servers']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Proxy Servers']])]); ?>
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

    <?php $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]'; ?>

    <div class="mb-5 grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
        <?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['icon' => 'mdi:server-network','iconClass' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400','label' => 'Total Servers','value' => $this->stats['total']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'mdi:server-network','icon-class' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400','label' => 'Total Servers','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['total'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['icon' => 'mdi:check-decagram','iconClass' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400','label' => 'Installed','value' => $this->stats['active']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'mdi:check-decagram','icon-class' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400','label' => 'Installed','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['active'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['icon' => 'mdi:cog-sync','iconClass' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400','label' => 'Installing','value' => $this->stats['installing']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'mdi:cog-sync','icon-class' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400','label' => 'Installing','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['installing'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['icon' => 'mdi:alert-circle','iconClass' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400','label' => 'Failed','value' => $this->stats['failed']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'mdi:alert-circle','icon-class' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400','label' => 'Failed','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->stats['failed'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
    </div>

    <div class="<?php echo e($cardClass); ?>">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">SecureProxy Servers</h3>
            <div class="flex gap-2">
                <button type="button" wire:click="loadServers"
                    class="flex h-10 items-center gap-1.5 rounded-lg border border-gray-200 px-3 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                    <iconify-icon icon="mdi:refresh" width="16" height="16"></iconify-icon>
                    Refresh
                </button>
                <button type="button" @click="$dispatch('open-modal-proxy-server')" wire:click="resetForm"
                    class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                    <iconify-icon icon="bx:plus" width="18" height="18"></iconify-icon>
                    Add Server
                </button>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loadError): ?>
            <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                <?php if (isset($component)) { $__componentOriginal784435fc350028aa1cabbd56f101a709 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal784435fc350028aa1cabbd56f101a709 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.alert','data' => ['type' => 'danger','message' => $loadError]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($loadError)]); ?>
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
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="overflow-x-auto border-t border-gray-100 dark:border-gray-800">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-5 py-3 font-medium">Server</th>
                        <th class="px-3 py-3 font-medium">IP</th>
                        <th class="px-3 py-3 font-medium">Ports (HTTP/SOCKS5/Agent)</th>
                        <th class="px-3 py-3 text-center font-medium">Status</th>
                        <th class="px-3 py-3 text-center font-medium">Setup</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $servers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $server): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $setupColors = [
                                'pending' => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                                'installing' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                'ready' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                'failed' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                            ];
                        ?>
                        <tr wire:key="proxy-server-<?php echo e($server['id']); ?>" class="transition-colors hover:bg-gray-200 dark:hover:bg-white/[0.05]">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-1.5 text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                    <?php if (isset($component)) { $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.flag','data' => ['flag' => $server['flag']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.flag'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flag' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($server['flag'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $attributes = $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $component = $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?>
                                    <?php echo e($server['name']); ?>

                                </div>
                                <div class="text-theme-xs text-gray-400"><?php echo e($server['country']); ?></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($server['infra_server_id'])): ?>
                                    <span class="mt-0.5 inline-block rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/5 dark:text-gray-400" title="Synced from VPS Monitor Infrastructure">From Infrastructure</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-3 py-3"><code class="text-theme-xs text-gray-500 dark:text-gray-400"><?php echo e($server['ip']); ?></code></td>
                            <td class="px-3 py-3 text-theme-sm text-gray-600 dark:text-gray-400"><?php echo e($server['proxy_port']); ?> / <?php echo e($server['socks_port']); ?> / <?php echo e($server['agent_port']); ?></td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($server['is_active'] ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400'); ?>">
                                    <?php echo e($server['is_active'] ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($setupColors[$server['setup_status']] ?? $setupColors['pending']); ?>">
                                    <?php echo e(ucfirst($server['setup_status'])); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($server['setup_log']): ?>
                                    <button type="button" wire:click="viewLog(<?php echo e($server['id']); ?>)" @click="$dispatch('open-modal-proxy-log')"
                                        class="ml-1 text-theme-xs font-medium text-brand-500 hover:underline">log</button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo e(route('proxy-servers.manage', $server['id'])); ?>" title="Manage"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                        <iconify-icon icon="famicons:stats-chart-outline" width="18" height="18"></iconify-icon>
                                    </a>
                                    <button type="button" wire:click="runSetup(<?php echo e($server['id']); ?>)" title="Run SSH setup"
                                        wire:loading.attr="disabled" wire:target="runSetup(<?php echo e($server['id']); ?>)"
                                        <?php if($server['setup_status'] === 'installing'): ?> disabled <?php endif; ?>
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-success-500 transition-colors hover:border-success-300 hover:bg-success-50 disabled:opacity-50 dark:border-gray-800 dark:hover:border-success-500/30 dark:hover:bg-success-500/10">
                                        <iconify-icon icon="mdi:cog-play" width="18" height="18" wire:loading.remove wire:target="runSetup(<?php echo e($server['id']); ?>)"></iconify-icon>
                                        <iconify-icon icon="radix-icons:reload" width="18" height="18" class="animate-spin" wire:loading wire:target="runSetup(<?php echo e($server['id']); ?>)"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="syncUsers(<?php echo e($server['id']); ?>)" title="Sync users"
                                        wire:loading.attr="disabled" wire:target="syncUsers(<?php echo e($server['id']); ?>)"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-blue-light-500 transition-colors hover:border-blue-light-300 hover:bg-blue-light-50 disabled:opacity-50 dark:border-gray-800 dark:hover:border-blue-light-500/30 dark:hover:bg-blue-light-500/10">
                                        <iconify-icon icon="mdi:sync" width="18" height="18" wire:loading.remove wire:target="syncUsers(<?php echo e($server['id']); ?>)"></iconify-icon>
                                        <iconify-icon icon="radix-icons:reload" width="18" height="18" class="animate-spin" wire:loading wire:target="syncUsers(<?php echo e($server['id']); ?>)"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="openTerminal(<?php echo e($server['id']); ?>)" @click="$dispatch('open-modal-proxy-terminal')" title="Terminal"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:border-gray-300 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:bg-white/5">
                                        <iconify-icon icon="mdi:console" width="18" height="18"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="editServer(<?php echo e($server['id']); ?>)" @click="$dispatch('open-modal-proxy-server')" title="Edit"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-warning-500 transition-colors hover:border-warning-300 hover:bg-warning-50 dark:border-gray-800 dark:hover:border-warning-500/30 dark:hover:bg-warning-500/10">
                                        <iconify-icon icon="material-symbols:edit" width="18" height="18"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="$js.confirmDeleteServer(<?php echo e($server['id']); ?>)" title="Delete"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 transition-colors hover:border-error-300 hover:bg-error-50 dark:border-gray-800 dark:hover:border-error-500/30 dark:hover:bg-error-500/10">
                                        <iconify-icon icon="mingcute:delete-2-line" width="18" height="18"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">
                                <iconify-icon icon="mdi:server-off" width="32" height="32" class="mx-auto mb-2 block opacity-50"></iconify-icon>
                                <?php echo e($loadError ? 'Unable to load proxy servers.' : 'No proxy servers found. Add one to get started.'); ?>

                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal806379accb82f5815113f9f84fdc97f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal806379accb82f5815113f9f84fdc97f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.modal','data' => ['name' => 'proxy-server','maxWidth' => 'max-w-[560px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'proxy-server','max-width' => 'max-w-[560px]']); ?>
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90"><?php echo e($isEdit ? 'Edit Proxy Server' : 'Add Proxy Server'); ?></h4>

        <?php
            $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
            $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
            $errorClass = 'mt-1 text-theme-xs text-error-500';
        ?>

        <form wire:submit.prevent="saveServer" class="space-y-4">
            <p class="text-theme-xs font-semibold uppercase tracking-wide text-gray-400">Connection</p>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <label class="<?php echo e($labelClass); ?>">Name</label>
                    <input type="text" wire:model="name" class="<?php echo e($inputClass); ?>">
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
                    <label class="<?php echo e($labelClass); ?>">Flag</label>
                    <input type="text" wire:model="flag" class="<?php echo e($inputClass); ?>">
                </div>
            </div>
            <div>
                <label class="<?php echo e($labelClass); ?>">Country</label>
                <input type="text" wire:model="country" class="<?php echo e($inputClass); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="<?php echo e($labelClass); ?> flex items-center gap-1.5">
                    IP Address
                    <span wire:loading wire:target="ip" class="text-theme-xs font-normal text-gray-400">· detecting location…</span>
                </label>
                <input type="text" wire:model.live.blur="ip" class="<?php echo e($inputClass); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($locationDetectError): ?>
                    <p class="mt-1 text-theme-xs text-warning-500"><?php echo e($locationDetectError); ?></p>
                <?php else: ?>
                    <p class="mt-1 text-theme-xs text-gray-400">Country &amp; flag auto-fill from this IP — edit either if it's wrong.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="<?php echo e($labelClass); ?>">HTTP Port</label>
                    <input type="number" wire:model="proxy_port" class="<?php echo e($inputClass); ?>">
                </div>
                <div>
                    <label class="<?php echo e($labelClass); ?>">SOCKS5 Port</label>
                    <input type="number" wire:model="socks_port" class="<?php echo e($inputClass); ?>">
                </div>
                <div>
                    <label class="<?php echo e($labelClass); ?>">Agent Port</label>
                    <input type="number" wire:model="agent_port" class="<?php echo e($inputClass); ?>">
                </div>
            </div>

            <p class="border-t border-gray-100 pt-4 text-theme-xs font-semibold uppercase tracking-wide text-gray-400 dark:border-gray-800">SSH Access</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="<?php echo e($labelClass); ?>">SSH User</label>
                    <input type="text" wire:model="ssh_user" class="<?php echo e($inputClass); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ssh_user'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="<?php echo e($labelClass); ?>">SSH Port</label>
                    <input type="number" wire:model="ssh_port" class="<?php echo e($inputClass); ?>">
                </div>
            </div>
            <div>
                <label class="<?php echo e($labelClass); ?>">SSH Password <?php echo e($isEdit ? '(leave blank to keep current)' : ''); ?></label>
                <input type="password" wire:model="ssh_password" class="<?php echo e($inputClass); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ssh_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </form>

        <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
            <button type="button" wire:click="resetForm" @click="$dispatch('close-modal-proxy-server')"
                class="rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                Close
            </button>
            <button type="button" wire:click="saveServer"
                class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                <?php echo e($isEdit ? 'Update' : 'Create'); ?>

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

    
    <?php if (isset($component)) { $__componentOriginal806379accb82f5815113f9f84fdc97f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal806379accb82f5815113f9f84fdc97f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.modal','data' => ['name' => 'proxy-log','maxWidth' => 'max-w-[700px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'proxy-log','max-width' => 'max-w-[700px]']); ?>
        <h4 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Setup Log</h4>
        <pre class="max-h-96 overflow-y-auto rounded-lg bg-gray-900 p-4 text-xs text-gray-100"><?php echo e($viewingLog); ?></pre>
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

    
    <?php if (isset($component)) { $__componentOriginal806379accb82f5815113f9f84fdc97f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal806379accb82f5815113f9f84fdc97f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.modal','data' => ['name' => 'proxy-terminal','maxWidth' => 'max-w-[800px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'proxy-terminal','max-width' => 'max-w-[800px]']); ?>
        <h4 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">Terminal</h4>
        <p class="mb-5 text-theme-xs text-gray-400"><?php echo e($selectedServerLabel); ?></p>

        <pre id="proxy-terminal-output" class="max-h-96 min-h-64 overflow-y-auto whitespace-pre-wrap rounded-lg bg-gray-900 p-4 font-mono text-xs text-gray-100"><?php echo e($terminalOutput); ?></pre>

        <div class="mt-5 flex flex-wrap gap-3">
            <button type="button" wire:click="connectSSH" wire:loading.attr="disabled" wire:target="connectSSH,executeScript,runQueueWorker"
                class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                <iconify-icon icon="mdi:connection" width="16" height="16"></iconify-icon>
                Run SSH
            </button>
            <button type="button" wire:click="executeScript" wire:loading.attr="disabled" wire:target="connectSSH,executeScript,runQueueWorker"
                class="flex items-center gap-1.5 rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90 disabled:cursor-not-allowed disabled:opacity-50">
                <iconify-icon icon="mdi:cog-play" width="16" height="16"></iconify-icon>
                Execute Setup Script
            </button>
            <button type="button" wire:click="runQueueWorker" wire:loading.attr="disabled" wire:target="connectSSH,executeScript,runQueueWorker"
                class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                <iconify-icon icon="mdi:play-circle-outline" width="16" height="16"></iconify-icon>
                Run Queue Worker
            </button>
            <span wire:loading wire:target="connectSSH,executeScript,runQueueWorker" class="flex items-center gap-1.5 text-theme-xs text-gray-400">
                <iconify-icon icon="radix-icons:reload" width="14" height="14" class="animate-spin"></iconify-icon>
                Working…
            </span>
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
        $__scriptKey = '4156763033-0';
        ob_start();
    ?>
<script>
    $js('confirmDeleteServer', (id) => {
        Swal.fire({
            title: 'Delete this proxy server?',
            text: 'All user credentials provisioned on this server will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.deleteServer(id);
            }
        });
    });

    $wire.on('closeModal', (event) => {
        const name = event.model === 'proxyLogModal' ? 'proxy-log' : 'proxy-server';
        window.dispatchEvent(new CustomEvent('close-modal-' + name));
    });

    $wire.on('sweetAlert', (event) => {
        Swal.fire({
            title: event.title,
            text: event.message,
            icon: event.type,
            timer: 2500,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    });

    $wire.on('scroll-terminal', () => {
        $nextTick(() => {
            const el = document.getElementById('proxy-terminal-output');
            if (el) {
                el.scrollTop = el.scrollHeight;
            }
        });
    });
</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/proxy-servers.blade.php ENDPATH**/ ?>