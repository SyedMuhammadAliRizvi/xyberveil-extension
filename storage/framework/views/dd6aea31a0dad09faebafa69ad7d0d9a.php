<!doctype html>
<html lang="en">

<head>
    <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body class="bg-theme bg-theme2">

    <!--wrapper-->
    <div class="wrapper">
        <!--start page content -->
        <?php echo $__env->yieldContent('content'); ?>
        <!--end page content -->
    </div>
    <!--end wrapper-->

    <?php echo $__env->make('partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/layouts/guest.blade.php ENDPATH**/ ?>