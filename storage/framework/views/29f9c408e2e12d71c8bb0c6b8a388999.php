<?php $__env->startSection('title', 'Login'); ?>
<div>
    <h2 class="text-xl font-bold text-center text-slate-900">Welcome Back</h2>
    <p class="mt-1 mb-6 text-sm text-center text-slate-400">Sign in to your account to continue</p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('message')): ?>
        <?php if (isset($component)) { $__componentOriginal784435fc350028aa1cabbd56f101a709 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal784435fc350028aa1cabbd56f101a709 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.alert','data' => ['type' => 'danger','message' => $errors->first('message')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('message'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal784435fc350028aa1cabbd56f101a709)): ?>
<?php $attributes = $__attributesOriginal784435fc350028aa1cabbd56f101a709; ?>
<?php unset($__attributesOriginal784435fc350028aa1cabbd56f101a709); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal784435fc350028aa1cabbd56f101a709)): ?>
<?php $component = $__componentOriginal784435fc350028aa1cabbd56f101a709; ?>
<?php unset($__componentOriginal784435fc350028aa1cabbd56f101a709); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block mb-1.5 text-xs font-bold tracking-wide text-slate-500 uppercase">Email</label>
            <input type="email" wire:model="email" placeholder="jhon@example.com" autofocus
                class="w-full rounded-lg border border-slate-200 bg-sky-50/60 px-4 py-3 text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-theme-xs text-error-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div x-data="{ show: false }">
            <label class="block mb-1.5 text-xs font-bold tracking-wide text-slate-500 uppercase">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="Enter Password"
                    class="w-full rounded-lg border border-slate-200 bg-sky-50/60 px-4 py-3 pr-12 text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-slate-600">
                    <i class="fa-regular text-[16px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-theme-xs text-error-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <label class="flex items-center gap-2 text-xs font-bold tracking-wide text-slate-500 uppercase">
            <input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-brand-500 focus:ring-brand-400">
            Remember me
        </label>

        <button type="submit" wire:loading.attr="disabled" wire:target="login"
            class="w-full rounded-lg bg-brand-500 py-3 font-bold uppercase tracking-wide text-white transition hover:bg-brand-600 disabled:cursor-not-allowed disabled:opacity-70">
            <span wire:loading.remove wire:target="login">Login</span>
            <span wire:loading wire:target="login">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </span>
        </button>

        <a href="#" class="block text-center text-sm text-slate-500 hover:text-brand-600">Forgot your password?</a>
    </form>
</div>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/auth/login.blade.php ENDPATH**/ ?>