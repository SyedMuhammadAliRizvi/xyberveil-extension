<?php if (isset($component)) { $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.flag','data' => ['flag' => $flag]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.flag'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flag' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($flag)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $attributes = $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $component = $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\storage\framework\views/97590646387c114e4681b6cf1d1b1726.blade.php ENDPATH**/ ?>