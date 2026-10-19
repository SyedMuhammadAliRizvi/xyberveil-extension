<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- This project is light-mode only — dark mode has been intentionally removed. -->
<script>
    document.documentElement.setAttribute('data-bs-theme', 'light');
</script>
<!--favicon-->
<link rel="icon" href="<?php echo e(asset('assets/images/favicon-32x32.png')); ?>" type="image/png" />
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/images/apple-touch-icon.png')); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/images/favicon-32x32.png')); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/images/favicon-16x16.png')); ?>">
<link rel="manifest" href="<?php echo e(asset('assets/images/site.webmanifest')); ?>">
<!--plugins-->
<link href="<?php echo e(asset('assets/plugins/simplebar/css/simplebar.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('assets/plugins/metismenu/css/metisMenu.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('assets/plugins/sweetalert/custom-sweetalert.css')); ?>" rel="stylesheet" />
<!-- Bootstrap CSS -->
<link href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/bootstrap-extended.css')); ?>" rel="stylesheet">
<!-- Font Link -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
<!-- App CSS -->
<link href="<?php echo e(asset('assets/css/app.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/icons.css')); ?>" rel="stylesheet">

<link href="<?php echo e(asset('assets/css/custom.css')); ?>?v=<?php echo e(filemtime(public_path('assets/css/custom.css'))); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/theme-dark.css')); ?>?v=<?php echo e(filemtime(public_path('assets/css/theme-dark.css'))); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/theme-light.css')); ?>?v=<?php echo e(filemtime(public_path('assets/css/theme-light.css'))); ?>" rel="stylesheet">

<title> <?php echo $__env->yieldContent('title'); ?> | <?php echo e(config('app.name')); ?> </title>

<?php echo $__env->yieldContent('styles'); ?><?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/partials/head.blade.php ENDPATH**/ ?>