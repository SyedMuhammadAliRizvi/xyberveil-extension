<?php $__env->startSection('title', 'Configuration'); ?>
<?php $__env->startSection('styles'); ?>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        /* Force Quill editors to have light theme */
        .ql-editor {
            background-color: #ffffff !important;
            color: #000000 !important;
        }

        .ql-toolbar {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #e9ecef !important;
        }

        .ql-container {
            background-color: #ffffff !important;
            border: 1px solid #e9ecef !important;
        }

        .ql-toolbar .ql-stroke {
            stroke: #444 !important;
        }

        .ql-toolbar .ql-fill {
            fill: #444 !important;
        }

        .ql-toolbar button:hover {
            background-color: #e9ecef !important;
        }

        .ql-toolbar button.ql-active {
            background-color: #007bff !important;
            color: white !important;
        }

        .ql-snow .ql-tooltip {
            background-color: #ffffff !important;
            border: 1px solid #ccc !important;
            color: #000000 !important;
        }

        .ql-snow .ql-tooltip input[type=text] {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #ccc !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<div>
    <?php if (isset($component)) { $__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.breadcrumb','data' => ['items' => [['label' => 'Configuration']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Configuration']])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a)): ?>
<?php $attributes = $__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a; ?>
<?php unset($__attributesOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a)): ?>
<?php $component = $__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a; ?>
<?php unset($__componentOriginalbb4c29cc7fe1cdfc39ba032585b6ac0a); ?>
<?php endif; ?>

    <?php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    ?>

    <div class="mb-5 flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800">
        <button type="button" wire:click="$set('navTab', 'email')"
            class="border-b-2 px-4 py-3 text-theme-sm font-medium transition-colors <?php echo e($navTab === 'email' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'); ?>">
            Email
        </button>
        <button type="button" wire:click="$set('navTab', 'tos_privacy')"
            class="border-b-2 px-4 py-3 text-theme-sm font-medium transition-colors <?php echo e($navTab === 'tos_privacy' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'); ?>">
            ToS & Privacy
        </button>
        <button type="button" wire:click="$set('navTab', 'monitoring')"
            class="border-b-2 px-4 py-3 text-theme-sm font-medium transition-colors <?php echo e($navTab === 'monitoring' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'); ?>">
            Monitoring API
        </button>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($navTab === 'email'): ?>
        <div class="<?php echo e($cardClass); ?> max-w-2xl">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Email Configuration</h3>
            </div>
            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                <form wire:submit.prevent="emailConfig" class="space-y-4">
                    <div>
                        <label class="<?php echo e($labelClass); ?>">SMTP Host</label>
                        <input type="text" placeholder="Enter SMTP Host" wire:model="host" class="<?php echo e($inputClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="<?php echo e($labelClass); ?>">SMTP Port</label>
                        <input type="text" placeholder="Enter SMTP Port" wire:model="port" class="<?php echo e($inputClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="<?php echo e($labelClass); ?>">SMTP Username</label>
                        <input type="text" placeholder="Enter SMTP Username" wire:model.live="username" class="<?php echo e($inputClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div x-data="{ show: false }">
                        <label class="<?php echo e($labelClass); ?>">SMTP Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" placeholder="Enter your password" wire:model="password" class="<?php echo e($inputClass); ?> pr-11">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <iconify-icon :icon="show ? 'fluent:eye-off-20-regular' : 'fluent:eye-20-regular'" width="18" height="18"></iconify-icon>
                            </button>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="<?php echo e($labelClass); ?>">SMTP Address</label>
                        <input type="text" placeholder="Enter SMTP Address" wire:model="address" class="<?php echo e($inputClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="<?php echo e($labelClass); ?>">SMTP Name</label>
                        <input type="text" placeholder="Enter SMTP Name" wire:model="name" class="<?php echo e($inputClass); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="border-t border-gray-100 pt-5 dark:border-gray-800">
                        <button type="submit"
                            class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif($navTab === 'tos_privacy'): ?>
        <div class="<?php echo e($cardClass); ?>">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">ToS & Privacy Configuration</h3>
            </div>
            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                <form wire:submit.prevent="policyConfig" class="space-y-4">
                    <div>
                        <label class="<?php echo e($labelClass); ?>">Refund Policy</label>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('quill-text-editor', ['wire:model.live' => 'refund_policy','theme' => 'snow']);

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1664318989-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['refund_policy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="<?php echo e($labelClass); ?>">Terms Of Service</label>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('quill-text-editor', ['wire:model.live' => 'tos','theme' => 'snow']);

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1664318989-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="<?php echo e($labelClass); ?>">Privacy Policy</label>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('quill-text-editor', ['wire:model.live' => 'privacy_policy','theme' => 'snow']);

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1664318989-2', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['privacy_policy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="<?php echo e($errorClass); ?>"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="border-t border-gray-100 pt-5 dark:border-gray-800">
                        <button type="submit"
                            class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif($navTab === 'monitoring'): ?>
        <div class="<?php echo e($cardClass); ?> max-w-3xl">
            <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Monitoring API Configuration</h3>
                <span class="rounded-full bg-blue-light-50 px-2.5 py-1 text-xs font-semibold text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">VPS Monitoring Access</span>
            </div>
            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $monitor_api_token): ?>
                    <div class="mb-4 rounded-lg border border-blue-light-200 bg-blue-light-50 px-4 py-3 text-theme-sm text-blue-light-700 dark:border-blue-light-500/30 dark:bg-blue-light-500/10 dark:text-blue-light-400">
                        <iconify-icon icon="mdi:information" width="18" height="18" class="mr-1 align-text-bottom"></iconify-icon>
                        No monitoring API token exists. Generate one to enable VPS monitoring API access.
                    </div>

                    <button type="button" wire:click="generateMonitoringToken"
                        class="flex items-center gap-1.5 rounded-lg bg-success-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-success-600">
                        <iconify-icon icon="mdi:key-plus" width="18" height="18"></iconify-icon>
                        Generate API Token
                    </button>
                <?php else: ?>
                    <div class="mb-4">
                        <label class="<?php echo e($labelClass); ?>">Current API Token</label>
                        <div class="flex gap-2">
                            <input type="text" value="<?php echo e($monitor_api_token); ?>" readonly class="<?php echo e($inputClass); ?> font-mono opacity-70">
                            <button type="button" wire:click="regenerateMonitoringToken"
                                onclick="return confirm('This will invalidate the current token. Continue?')"
                                class="flex h-11 shrink-0 items-center gap-1.5 rounded-lg border border-brand-200 px-3 text-theme-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                                <iconify-icon icon="mdi:refresh" width="16" height="16"></iconify-icon>
                                Regenerate
                            </button>
                        </div>
                        <p class="mt-1 text-theme-xs text-gray-400">Use this token for VPS monitoring API authentication</p>
                    </div>

                    <div class="mb-4 rounded-lg border border-warning-200 bg-warning-50 px-4 py-3 text-theme-sm text-warning-700 dark:border-warning-500/30 dark:bg-warning-500/10 dark:text-warning-400">
                        <iconify-icon icon="mdi:security" width="18" height="18" class="mr-1 align-text-bottom"></iconify-icon>
                        <strong>Security Notice:</strong> Keep this token secure. Anyone with this token can access your VPS monitoring data.
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-4 text-theme-sm text-gray-600 dark:border-gray-800 dark:bg-white/3 dark:text-gray-400">
                        <h6 class="mb-2 flex items-center gap-1.5 font-semibold text-gray-800 dark:text-white/90">
                            <iconify-icon icon="mdi:api" width="18" height="18"></iconify-icon>
                            API Usage Information
                        </h6>
                        <p class="mb-1"><strong class="text-gray-700 dark:text-gray-300">Base URL:</strong> <code><?php echo e(config('app.url')); ?>/api/vps</code></p>
                        <p class="mb-2"><strong class="text-gray-700 dark:text-gray-300">Authentication:</strong> Include header <code>Authorization: Bearer <?php echo e($monitor_api_token); ?></code></p>
                        <p class="mb-1"><strong class="text-gray-700 dark:text-gray-300">Available Endpoints:</strong></p>
                        <ul class="mt-1 list-disc space-y-1 pl-5">
                            <li><code>GET /dashboard</code> - Dashboard overview with all servers</li>
                            <li><code>GET /overview?range=24h</code> - All servers overview with time range</li>
                            <li><code>GET /servers/{id}/metrics?range=1w</code> - Server metrics with time range</li>
                            <li><code>GET /servers/{id}/live</code> - Real-time server data</li>
                            <li><code>POST /compare</code> - Compare multiple servers (JSON: {"servers": [1,2,3], "range": "24h"})</li>
                        </ul>
                        <p class="mt-3 text-theme-xs text-gray-400">
                            <strong>Time Ranges:</strong> live (30min), 24h, 1w, 1m, 3m
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="<?php echo e($cardClass); ?> p-5 sm:p-6">
            <h5 class="text-base font-semibold text-gray-800 dark:text-white/90">Default Configuration</h5>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<?php $__env->stopSection(); ?>
    <?php
        $__scriptKey = '1664318989-0';
        ob_start();
    ?>
    <script>
        $wire.on('sweetToast', (event) => {
            Swal.fire({
                title: event.message,
                icon: event.type,
                timer: 2000,
                toast: true,
                position: 'top-end',
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/livewire/admin/configuration.blade.php ENDPATH**/ ?>