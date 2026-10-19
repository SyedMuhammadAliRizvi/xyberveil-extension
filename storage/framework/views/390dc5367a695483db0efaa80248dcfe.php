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
        // The sidebar collapse/expand choice is a user preference that must
        // survive normal (non-SPA) page navigation, so it's persisted here
        // instead of only living in Alpine's in-memory store.
        function resolveSidebarExpanded(fallback) {
            try {
                const saved = localStorage.getItem('sidebarExpanded');
                if (saved !== null) {
                    return saved === 'true';
                }
            } catch (e) {
                // localStorage unavailable (privacy mode, etc.) — fall through.
            }

            return fallback;
        }

        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', { theme: 'light' });

            Alpine.store('sidebar', {
                isExpanded: resolveSidebarExpanded(false),
                isMobileOpen: false,
                isHovered: false,
                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                    try {
                        localStorage.setItem('sidebarExpanded', String(this.isExpanded));
                    } catch (e) {
                        // Ignore — nothing to persist to.
                    }
                },
                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },
                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },
                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                },
            });
        });
    </script>

    <script src="<?php echo e(asset('assets/plugins/iconify/iconify-icon.min.js')); ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?php echo e(asset('assets/plugins/sweetalert/custom-sweetalert.css')); ?>" rel="stylesheet" />

    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="bg-[#eef0fb]" x-data="{ loaded: true }"
    x-init="$store.sidebar.isExpanded = resolveSidebarExpanded(false);
        window.addEventListener('resize', () => {
            if (window.innerWidth < 1280) {
                $store.sidebar.setMobileOpen(false);
                $store.sidebar.isExpanded = false;
            } else {
                $store.sidebar.isMobileOpen = false;
                $store.sidebar.isExpanded = resolveSidebarExpanded(false);
            }
        });">

    <div class="min-h-screen xl:flex" x-cloak>
        <?php echo $__env->make('layouts.tailadmin-backdrop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('layouts.tailadmin-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 transition-all duration-300 ease-in-out" :class="{
            'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
            'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        }">
            <?php echo $__env->make('layouts.tailadmin-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('assets/plugins/sweetalert/sweetalert.js')); ?>"></script>

    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/layouts/tailadmin.blade.php ENDPATH**/ ?>