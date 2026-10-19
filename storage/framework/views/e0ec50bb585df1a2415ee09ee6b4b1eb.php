<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['flag' => null, 'size' => 'w-5 h-3.5']));

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

foreach (array_filter((['flag' => null, 'size' => 'w-5 h-3.5']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Flags are stored as a regional-indicator-symbol emoji (e.g. 🇵🇱), but
    // Windows' default emoji font often has no real flag glyph for less-common
    // countries and silently falls back to rendering the two letters as plain
    // text ("PL") instead. Decoding the emoji back to its ISO code and showing
    // an actual flag image renders identically everywhere.
    $code = null;

    if ($flag) {
        $letters = [];
        $len = mb_strlen($flag);

        for ($i = 0; $i < $len; $i++) {
            $point = mb_ord(mb_substr($flag, $i, 1));

            if ($point !== false && $point >= 127462 && $point <= 127487) {
                $letters[] = chr($point - 127397);
            }
        }

        if (count($letters) === 2) {
            $code = strtolower(implode('', $letters));
        }
    }
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($code): ?>
    <img
        src="https://flagcdn.com/w40/<?php echo e($code); ?>.png"
        alt="<?php echo e(strtoupper($code)); ?>"
        loading="lazy"
        <?php echo e($attributes->merge(['class' => $size.' shrink-0 rounded-sm object-cover align-middle shadow-xs'])); ?>

        onerror="this.outerHTML='<span class=&quot;align-middle&quot;><?php echo e($flag); ?></span>'"
    >
<?php else: ?>
    <span <?php echo e($attributes->merge(['class' => 'align-middle'])); ?>><?php echo e($flag ?: '🌐'); ?></span>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/components/tailadmin/flag.blade.php ENDPATH**/ ?>