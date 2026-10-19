<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'subtitle' => '',
    'chartId',
    'note' => '',
    'zoomable' => false,
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
    'title',
    'subtitle' => '',
    'chartId',
    'note' => '',
    'zoomable' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<div <?php echo e($attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-5 transition-colors duration-200 hover:border-accent dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-accent sm:p-6'])); ?>>
    <div class="flex items-start justify-between">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90"><?php echo e($title); ?></h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subtitle): ?>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400"><?php echo e($subtitle); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($zoomable): ?>
            <?php if (isset($component)) { $__componentOriginalb508698af2cd78930bc723495573d0bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb508698af2cd78930bc723495573d0bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.expand-button','data' => ['chartId' => $chartId,'title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.expand-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chart-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chartId),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $attributes = $__attributesOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__attributesOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $component = $__componentOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__componentOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div id="<?php echo e($chartId); ?>" class="mx-auto -mt-2" style="max-width:280px; min-height:220px"></div>

    
    <div id="<?php echo e($chartId); ?>-extra">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($note): ?>
            <p class="mx-auto -mt-4 max-w-xs text-center text-theme-sm text-gray-500 dark:text-gray-400"><?php echo e($note); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($footer)): ?>
            <div class="mt-4 flex items-center justify-center gap-5 rounded-xl border-t border-gray-100 pt-4 dark:border-gray-800">
                <?php echo e($footer); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/components/tailadmin/gauge-card.blade.php ENDPATH**/ ?>