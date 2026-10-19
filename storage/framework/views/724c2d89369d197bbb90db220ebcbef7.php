<!doctype html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="icon" href="<?php echo e(asset('assets/images/favicon-32x32.png')); ?>" type="image/png" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/images/apple-touch-icon.png')); ?>">

    <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e(config('app.name')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', { theme: 'light' });
        });
    </script>

    <script src="<?php echo e(asset('assets/plugins/iconify/iconify-icon.min.js')); ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?php echo e(asset('assets/plugins/sweetalert/custom-sweetalert.css')); ?>" rel="stylesheet" />

    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="bg-[#eef0fb]">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">

        <div class="mb-6 flex flex-col items-center">
            <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="<?php echo e(config('app.name')); ?>" class="mb-3 h-16 w-16">
            <h1 class="text-2xl font-bold tracking-tight">
                <span class="text-brand-500">XyberVeil VPN</span>
                <span class="text-slate-900">Extension</span>
            </h1>
        </div>

        <div class="w-full max-w-md rounded-xl border border-slate-100 bg-white p-8 shadow-sm">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

    </div>

    <script src="<?php echo e(asset('assets/plugins/sweetalert/sweetalert.js')); ?>"></script>

    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/layouts/tailadmin-guest.blade.php ENDPATH**/ ?>