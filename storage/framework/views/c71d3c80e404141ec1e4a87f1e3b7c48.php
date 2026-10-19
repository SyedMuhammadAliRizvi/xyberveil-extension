<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon',
    'iconClass' => 'bg-gray-100 text-gray-800 dark:bg-white/5 dark:text-white/90',
    'label',
    'value',
    'valueId' => null,
    'trend' => null, 
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'icon',
    'iconClass' => 'bg-gray-100 text-gray-800 dark:bg-white/5 dark:text-white/90',
    'label',
    'value',
    'valueId' => null,
    'trend' => null, 
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<div <?php echo e($attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-5 transition-colors duration-200 hover:border-accent dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-accent md:p-6'])); ?>>
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl <?php echo e($iconClass); ?>">
        <iconify-icon icon="<?php echo e($icon); ?>" width="26" height="26"></iconify-icon>
    </div>

    <div class="mt-5 flex items-end justify-between">
        <div>
            <p class="text-theme-sm text-gray-500 dark:text-gray-400"><?php echo e($label); ?></p>
            <h4 <?php if($valueId): ?> id="<?php echo e($valueId); ?>" <?php endif; ?> class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                <?php echo e($value); ?>

            </h4>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trend): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($trend)): ?>
                <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-theme-sm font-medium <?php echo e($trend['direction'] === 'up' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400'); ?>">
                    <iconify-icon icon="<?php echo e($trend['direction'] === 'up' ? 'bxs:up-arrow' : 'bxs:down-arrow'); ?>" width="10" height="10"></iconify-icon>
                    <?php echo e($trend['text']); ?>

                </span>
            <?php else: ?>
                
                <?php echo e($trend); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php echo e($slot ?? ''); ?>

</div>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/components/tailadmin/metric-card.blade.php ENDPATH**/ ?>