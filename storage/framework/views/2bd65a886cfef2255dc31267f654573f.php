<div wire:ignore>
    <div id="<?php echo e($quillId); ?>"></div>
</div>

    <?php
        $__scriptKey = '3250666238-0';
        ob_start();
    ?>
<script>
    const quill = new Quill('#' + <?php echo \Illuminate\Support\Js::from($quillId)->toHtml() ?>, {
        theme: <?php echo \Illuminate\Support\Js::from($theme)->toHtml() ?>
    });
    
    quill.root.innerHTML = $wire.get('value');
    
    quill.on('text-change', function () {
        let value = quill.root.innerHTML;
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('value', value);
    });
</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\vendor\dasundev\livewire-quill-text-editor\resources\views/livewire/quill-text-editor.blade.php ENDPATH**/ ?>