<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'wireModel',
    'label',
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
    'wireModel',
    'label',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<label <?php echo e($attributes->merge(['class' => 'flex items-center gap-3 cursor-pointer'])); ?>>
    <span class="relative inline-flex h-6 w-11 shrink-0 items-center">
        <input type="checkbox" <?php if($wireModel): ?> wire:model="<?php echo e($wireModel); ?>" <?php endif; ?> class="peer sr-only">
        <span class="absolute inset-0 rounded-full bg-gray-200 transition-colors peer-checked:bg-brand-500 dark:bg-white/10"></span>
        <span class="absolute left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
    </span>
    <span class="text-theme-sm text-gray-700 dark:text-gray-300"><?php echo e($label); ?></span>
</label>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/components/tailadmin/toggle.blade.php ENDPATH**/ ?>