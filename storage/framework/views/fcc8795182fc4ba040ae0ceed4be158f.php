<?php $__env->startSection('title', __('Maintenance Mode')); ?>
<?php $__env->startSection('content'); ?>
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-8 mx-auto">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="p-4 text-center">
                                <div class="mb-3 text-center">
                                    <img src="<?php echo e(asset('assets/images/logo.png')); ?>" width="90px" alt="logo" />
                                </div>
                                <div class="mb-0 text-center">
                                    <img src="<?php echo e(asset('assets/images/errors/404.png')); ?>" width="200px" alt="logo" />
                                </div>
                                <h3 class="mt-2">Page Not Found</h3>
                                <p class="lead">
                                    Sorry, the page you are looking for does not exist.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/errors/404.blade.php ENDPATH**/ ?>