<?php $__env->startSection('title', 'App Downloads'); ?>
<div>
    <?php if (isset($component)) { $__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [['label' => 'App Downloads']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'App Downloads']])]); ?>
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
    <div class="mb-5 flex items-center justify-between gap-3">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">App Downloads</h2>
        <a href="<?php echo e(route('app.downloads.add')); ?>" class="inline-flex h-10 items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white hover:bg-brand-600"><iconify-icon icon="ic:baseline-plus" width="18" height="18"></iconify-icon>Add Download</a>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-wrap gap-3 border-b border-gray-100 p-5 dark:border-gray-800">
            <input wire:model.live.500ms="search" placeholder="Search version..." class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-sm dark:border-gray-800 dark:text-white/90">
            <select wire:model.live="platformFilter" class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-sm dark:border-gray-800 dark:text-white/90"><option value="">All platforms</option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>"><?php echo e($name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></select>
            <select wire:model.live="sourceFilter" class="h-10 rounded-lg border border-gray-200 bg-transparent px-3 text-sm dark:border-gray-800 dark:text-white/90"><option value="">All sources</option><option value="url">External URL</option><option value="upload">Uploaded</option></select>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-gray-50 dark:bg-gray-900"><tr class="text-xs uppercase text-gray-500"><th class="px-5 py-3">Platform</th><th class="px-3 py-3">Version</th><th class="px-3 py-3">Source</th><th class="px-3 py-3">Downloads</th><th class="px-3 py-3">Status</th><th class="px-5 py-3">Actions</th></tr></thead><tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $downloads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $download): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="hover:bg-gray-50 dark:hover:bg-white/5"><td class="px-5 py-3 text-sm text-gray-700 dark:text-gray-300"><?php echo e($platforms[$download->platform] ?? $download->platform); ?></td><td class="px-3 py-3 text-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($download->version); ?></td><td class="px-3 py-3 text-sm text-gray-600 dark:text-gray-400"><?php echo e($download->isUpload() ? 'Uploaded' : 'External URL'); ?></td><td class="px-3 py-3 text-sm text-gray-600 dark:text-gray-400"><?php echo e(number_format($download->download_count)); ?></td><td class="px-3 py-3"><button wire:click="toggleActive(<?php echo e($download->id); ?>)" class="text-xs font-medium <?php echo e($download->is_active ? 'text-success-500' : 'text-error-500'); ?>"><?php echo e($download->is_active ? 'Active' : 'Inactive'); ?></button></td><td class="px-5 py-3"><div class="flex gap-2"><a title="View" href="<?php echo e(route('app.downloads.show', $download)); ?>" class="text-gray-500 hover:text-brand-500"><iconify-icon icon="mdi:eye-outline" width="19" height="19"></iconify-icon></a><a title="Edit" href="<?php echo e(route('app.downloads.edit', $download)); ?>" class="text-gray-500 hover:text-brand-500"><iconify-icon icon="material-symbols:edit-outline" width="19" height="19"></iconify-icon></a><button title="Delete" wire:click="deleteDownload(<?php echo e($download->id); ?>)" class="text-gray-500 hover:text-error-500"><iconify-icon icon="mingcute:delete-2-line" width="19" height="19"></iconify-icon></button></div></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">No downloads found.</td></tr><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody></table></div><div class="p-5"><?php echo e($downloads->links()); ?></div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/all-app-downloads.blade.php ENDPATH**/ ?>