<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'info', 
    'message' => '',
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
    'type' => 'info', 
    'message' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $styles = [
        'info' => 'bg-blue-light-50 text-blue-light-700 border-blue-light-200 dark:bg-blue-light-500/10 dark:text-blue-light-400 dark:border-blue-light-500/20',
        'success' => 'bg-success-50 text-success-700 border-success-200 dark:bg-success-500/10 dark:text-success-400 dark:border-success-500/20',
        'warning' => 'bg-warning-50 text-warning-700 border-warning-200 dark:bg-warning-500/10 dark:text-warning-400 dark:border-warning-500/20',
        'danger' => 'bg-error-50 text-error-700 border-error-200 dark:bg-error-500/10 dark:text-error-400 dark:border-error-500/20',
    ];
?>

<div x-data="{ show: true }" x-show="show"
    class="mb-5 flex items-start justify-between gap-3 rounded-xl border px-4 py-3 text-theme-sm <?php echo e($styles[$type] ?? $styles['info']); ?>">
    <span><?php echo e($message); ?></span>
    <button type="button" @click="show = false" class="shrink-0 opacity-70 hover:opacity-100">
        <iconify-icon icon="bx:x" width="18" height="18"></iconify-icon>
    </button>
</div>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/components/tailadmin/alert.blade.php ENDPATH**/ ?>