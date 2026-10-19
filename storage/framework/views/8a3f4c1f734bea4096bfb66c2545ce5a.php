<?php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
?>

<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasPages()): ?>
        <nav class="flex flex-col items-center justify-between gap-3 sm:flex-row">
            <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                <?php echo e(__('Showing')); ?>

                <span class="font-medium text-gray-800 dark:text-white/90"><?php echo e($paginator->firstItem()); ?></span>
                <?php echo e(__('to')); ?>

                <span class="font-medium text-gray-800 dark:text-white/90"><?php echo e($paginator->lastItem()); ?></span>
                <?php echo e(__('of')); ?>

                <span class="font-medium text-gray-800 dark:text-white/90"><?php echo e($paginator->total()); ?></span>
                <?php echo e(__('results')); ?>

            </p>

            <ul class="flex items-center gap-1">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->onFirstPage()): ?>
                    <li>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-300 dark:text-gray-700">
                            <iconify-icon icon="bx:chevron-left" width="18" height="18"></iconify-icon>
                        </span>
                    </li>
                <?php else: ?>
                    <li>
                        <button type="button"
                            wire:click="previousPage('<?php echo e($paginator->getPageName()); ?>')"
                            x-on:click="<?php echo e($scrollIntoViewJsSnippet); ?>" wire:loading.attr="disabled"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                            <iconify-icon icon="bx:chevron-left" width="18" height="18"></iconify-icon>
                        </button>
                    </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_string($element)): ?>
                        <li>
                            <span class="flex h-9 w-9 items-center justify-center text-theme-sm text-gray-400"><?php echo e($element); ?></span>
                        </li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($element)): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page == $paginator->currentPage()): ?>
                                <li wire:key="paginator-<?php echo e($paginator->getPageName()); ?>-page-<?php echo e($page); ?>">
                                    <span aria-current="page"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-500 text-theme-sm font-medium text-white">
                                        <?php echo e($page); ?>

                                    </span>
                                </li>
                            <?php else: ?>
                                <li wire:key="paginator-<?php echo e($paginator->getPageName()); ?>-page-<?php echo e($page); ?>">
                                    <button type="button"
                                        wire:click="gotoPage(<?php echo e($page); ?>, '<?php echo e($paginator->getPageName()); ?>')"
                                        x-on:click="<?php echo e($scrollIntoViewJsSnippet); ?>"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                                        <?php echo e($page); ?>

                                    </button>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasMorePages()): ?>
                    <li>
                        <button type="button"
                            wire:click="nextPage('<?php echo e($paginator->getPageName()); ?>')"
                            x-on:click="<?php echo e($scrollIntoViewJsSnippet); ?>" wire:loading.attr="disabled"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                            <iconify-icon icon="bx:chevron-right" width="18" height="18"></iconify-icon>
                        </button>
                    </li>
                <?php else: ?>
                    <li>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-300 dark:text-gray-700">
                            <iconify-icon icon="bx:chevron-right" width="18" height="18"></iconify-icon>
                        </span>
                    </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/components/tailadmin/pagination.blade.php ENDPATH**/ ?>