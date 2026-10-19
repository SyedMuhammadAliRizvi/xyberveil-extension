<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('assets/plugins/jsvectormap/css/jsvectormap.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $revMonthPct = $revenueLastMonth > 0
        ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
        : ($revenueThisMonth > 0 ? 100 : 0);

    $maxPlanRevenue = $topPlansByRevenue->max('revenue') ?: 1;

    $protocolLabels = ['Ready', 'Installing', 'Pending', 'Failed'];
    $protocolValues = [$readyProxyServers, $installingProxyServers, $pendingProxyServers, $failedProxyServers];
    $protocolTotalSafe = max(1, array_sum($protocolValues));
    $protocolPct = array_map(fn ($v) => round(($v / $protocolTotalSafe) * 100, 1), $protocolValues);

    $subLabels = array_keys($subscriptionsByStatus);
    $subValues = array_values($subscriptionsByStatus);
    $subColors = ['#00bae6', '#12b76a', '#f79009', '#98a2b3', '#f04438'];

    $devPlatforms = array_keys($devicesByPlatform);
    $devCounts = array_values($devicesByPlatform);

    $fetchedAt = $proxySummary['fetched_at'] ?? null;
    $fetchedAgo = $fetchedAt ? \Carbon\Carbon::parse($fetchedAt)->diffForHumans() : 'never';

    $maxServerConnected = $proxyServers->max('live_sessions') ?: 1;

    $avatarColors = ['#465fff', '#12b76a', '#0ba5ec', '#f79009', '#f04438', '#7a5af8', '#ee46bc', '#0086c9'];

    $cardClass = 'h-full rounded-2xl border border-gray-200 bg-white transition-colors duration-200 hover:border-accent dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-accent';

    $topCountries = collect($countryActiveSessions)->sortDesc()->take(3);
    $topCountriesMax = $topCountries->max() ?: 1;

    $hour = now()->hour;
    $greeting = 'Welcome';

    // Each widget's own markup is captured (content only, no wrapper) into
    // $widgets[id] below. $wrap() is called later, at assembly time, to add
    // the draggable wrapper + drag handle around that captured content —
    // this lets the SAME captured content be assembled two different ways:
    // the original nested default layout, or the flat fully-reorderable
    // grid once the admin has customized their own layout.
    $dragHandleHtml = '<button type="button" class="drag-handle absolute -left-2 -top-2 z-20 flex h-7 w-7 cursor-grab items-center justify-center rounded-full border border-gray-200 bg-white text-gray-400 opacity-0 shadow-theme-xs transition-opacity group-hover:opacity-100 hover:border-accent hover:text-accent active:cursor-grabbing dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500 dark:hover:border-accent dark:hover:text-accent" title="Drag to reorder" aria-label="Drag to reorder"><iconify-icon icon="mdi:drag" width="16" height="16"></iconify-icon></button>';

    // Widget width choices in the custom/flat layout — mirrors
    // DashboardController::WIDGET_SIZES exactly (key => Tailwind classes).
    $sizeClasses = [
        'sm' => 'md:col-span-6 xl:col-span-3',
        'md' => 'md:col-span-6 xl:col-span-6',
        'lg' => 'md:col-span-12 xl:col-span-8',
        'full' => 'md:col-span-12 xl:col-span-12',
    ];
    $sizeLabels = ['sm' => 'S', 'md' => 'M', 'lg' => 'L', 'full' => 'Full'];

    $sizePickerHtml = function (string $id, string $currentKey) use ($sizeLabels) {
        $buttons = '';
        foreach ($sizeLabels as $key => $label) {
            $isActive = $key === $currentKey;
            $activeClasses = $isActive
                ? ' bg-accent text-white'
                : ' text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/10';
            $buttons .= '<button type="button" class="widget-size-btn rounded px-1.5 py-0.5 text-[10px] font-semibold leading-none transition-colors'.$activeClasses.'" data-size="'.$key.'">'.$label.'</button>';
        }

        return '<div class="widget-size-picker absolute -right-2 -top-2 z-20 flex items-center gap-0.5 rounded-full border border-gray-200 bg-white p-1 opacity-0 shadow-theme-xs transition-opacity pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto dark:border-gray-700 dark:bg-gray-800" aria-label="Resize '.$id.'">'.$buttons.'</div>';
    };

    $widgets = [];
    // A regular closure capturing $widgets *by reference* — an arrow `fn()`
    // would capture it by value at definition time (i.e. still empty here,
    // before any widget content below has been captured into it).
    // $sizeKey is only passed for the flat/custom layout — the default
    // nested layout's sizes are fixed by its hand-tuned pairing, so it gets
    // no size picker at all (null skips rendering one).
    $wrap = function (string $id, string $extraClasses = '', ?string $sizeKey = null) use (&$widgets, $dragHandleHtml, $sizePickerHtml) {
        $picker = $sizeKey !== null ? $sizePickerHtml($id, $sizeKey) : '';
        $sizeAttr = $sizeKey !== null ? ' data-widget-size="'.$sizeKey.'"' : '';

        return '<div class="dashboard-widget group relative '.$extraClasses.'" data-widget-id="'.$id.'"'.$sizeAttr.'>'.$dragHandleHtml.$picker.($widgets[$id] ?? '').'</div>';
    };
?>


<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h4 class="text-2xl font-semibold text-gray-800 dark:text-white/90"><?php echo e($greeting); ?>, <?php echo e(Auth::user()->name ?? 'Admin'); ?></h4>
        <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
            <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e(number_format($proxySummary['sessions']['live'] ?? 0)); ?></span> users connected right now
        </p>
    </div>
    <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-300">
        <iconify-icon icon="bx:calendar" width="18" height="18" class="text-brand-500"></iconify-icon>
        <?php echo e(now()->format('l, jS F')); ?>

    </div>
</div>


<div x-show="$store.chartModal.open" x-cloak
    @keydown.escape.window="if ($store.chartModal.open) closeChartModal()"
    class="fixed inset-0 z-99999 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/60" @click="closeChartModal()"></div>
    <div class="relative w-full max-w-3xl rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xl dark:border-gray-800 dark:bg-gray-900 sm:p-6">
        <div class="mb-4 flex items-center justify-between">
            <h3 x-text="$store.chartModal.title" class="text-lg font-semibold text-gray-800 dark:text-white/90"></h3>
            <button type="button" @click="closeChartModal()"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/5 dark:hover:text-gray-300">
                <iconify-icon icon="bx:x" width="20" height="20"></iconify-icon>
            </button>
        </div>
        <div id="chart-modal-body"></div>
    </div>
</div>




<?php ob_start(); ?>
<?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['class' => 'h-full','icon' => 'mdi:vpn','iconClass' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400','label' => 'Live Proxy Sessions','valueId' => 'live-vpn-count','value' => number_format($proxySummary['sessions']['live'] ?? 0)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-full','icon' => 'mdi:vpn','icon-class' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400','label' => 'Live Proxy Sessions','value-id' => 'live-vpn-count','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($proxySummary['sessions']['live'] ?? 0))]); ?>
     <?php $__env->slot('trend', null, []); ?> 
        <span class="flex items-center gap-1.5 rounded-full bg-success-50 px-2.5 py-0.5 text-theme-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
            <span class="blink-dot"></span> Live
        </span>
     <?php $__env->endSlot(); ?>

    <p class="mt-5 text-theme-sm text-gray-500 dark:text-gray-400">
        <?php echo e(number_format($proxySummary['sessions']['total'] ?? 0)); ?> sessions today ·
        <?php echo e(number_format($proxySummary['servers']['active'] ?? 0)); ?>/<?php echo e(number_format($proxySummary['servers']['total'] ?? 0)); ?> servers active
    </p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php $widgets['kpi-live-vpn'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['class' => 'h-full','icon' => 'streamline:subscription-cashflow','iconClass' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400','label' => 'Revenue This Month','value' => '$' . number_format($revenueThisMonth, 0),'trend' => ['direction' => $revMonthPct >= 0 ? 'up' : 'down', 'text' => abs($revMonthPct) . '%']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-full','icon' => 'streamline:subscription-cashflow','icon-class' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400','label' => 'Revenue This Month','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$' . number_format($revenueThisMonth, 0)),'trend' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['direction' => $revMonthPct >= 0 ? 'up' : 'down', 'text' => abs($revMonthPct) . '%'])]); ?>
    <p class="mt-5 text-theme-sm text-gray-500 dark:text-gray-400">$<?php echo e(number_format($revenueLastMonth, 0)); ?> last month</p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php $widgets['kpi-revenue'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['class' => 'h-full','icon' => 'flowbite:users-group-outline','iconClass' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400','label' => 'Total Users','value' => number_format($totalUsers)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-full','icon' => 'flowbite:users-group-outline','icon-class' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400','label' => 'Total Users','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalUsers))]); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($newUsersToday > 0): ?>
         <?php $__env->slot('trend', null, []); ?> 
            <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-theme-sm font-medium text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">
                +<?php echo e($newUsersToday); ?> today
            </span>
         <?php $__env->endSlot(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php $widgets['kpi-total-users'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<?php if (isset($component)) { $__componentOriginalc06d16916621a39f37360ed89c168441 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc06d16916621a39f37360ed89c168441 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.metric-card','data' => ['class' => 'h-full','icon' => 'mdi:ticket-outline','iconClass' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400','label' => 'Open Tickets','value' => number_format($openTickets)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-full','icon' => 'mdi:ticket-outline','icon-class' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400','label' => 'Open Tickets','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($openTickets))]); ?>
    <p class="mt-5 text-theme-sm text-gray-500 dark:text-gray-400"><?php echo e(number_format($activeSubscriptions)); ?> active subscriptions</p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $attributes = $__attributesOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__attributesOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc06d16916621a39f37360ed89c168441)): ?>
<?php $component = $__componentOriginalc06d16916621a39f37360ed89c168441; ?>
<?php unset($__componentOriginalc06d16916621a39f37360ed89c168441); ?>
<?php endif; ?>
<?php $widgets['kpi-open-tickets'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<?php if (isset($component)) { $__componentOriginalb4bcee74c705907fb9b7b594ac89d9a4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4bcee74c705907fb9b7b594ac89d9a4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.gauge-card','data' => ['title' => 'Proxy Fleet Status','subtitle' => 'Fleet-wide proxy server setup health','chartId' => 'gaugeProxy','note' => ''.e($readyProxyServers).' of '.e($totalProxyServers).' servers ready','zoomable' => true,'class' => 'h-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.gauge-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Proxy Fleet Status','subtitle' => 'Fleet-wide proxy server setup health','chart-id' => 'gaugeProxy','note' => ''.e($readyProxyServers).' of '.e($totalProxyServers).' servers ready','zoomable' => true,'class' => 'h-full']); ?>
     <?php $__env->slot('footer', null, []); ?> 
        <div class="text-center">
            <span class="mx-auto mb-1 block h-2 w-2 rounded-full bg-success-500"></span>
            <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90"><?php echo e($readyProxyServers); ?></p>
            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Ready</p>
        </div>
        <div class="h-8 w-px bg-gray-200 dark:bg-gray-800"></div>
        <div class="text-center">
            <span class="mx-auto mb-1 block h-2 w-2 rounded-full bg-warning-500"></span>
            <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90"><?php echo e($installingProxyServers); ?></p>
            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Installing</p>
        </div>
        <div class="h-8 w-px bg-gray-200 dark:bg-gray-800"></div>
        <div class="text-center">
            <span class="mx-auto mb-1 block h-2 w-2 rounded-full bg-error-500"></span>
            <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90"><?php echo e($failedProxyServers); ?></p>
            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Failed</p>
        </div>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb4bcee74c705907fb9b7b594ac89d9a4)): ?>
<?php $attributes = $__attributesOriginalb4bcee74c705907fb9b7b594ac89d9a4; ?>
<?php unset($__attributesOriginalb4bcee74c705907fb9b7b594ac89d9a4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb4bcee74c705907fb9b7b594ac89d9a4)): ?>
<?php $component = $__componentOriginalb4bcee74c705907fb9b7b594ac89d9a4; ?>
<?php unset($__componentOriginalb4bcee74c705907fb9b7b594ac89d9a4); ?>
<?php endif; ?>
<?php $widgets['vps-health-gauge'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Active Sessions by Server Location</h3>
            <p id="map-updated" class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Updated <?php echo e($fetchedAgo); ?> · by server country, not client location</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-success-50 px-2.5 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                <span class="blink-dot"></span> Live
            </span>
            <?php if (isset($component)) { $__componentOriginalb508698af2cd78930bc723495573d0bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb508698af2cd78930bc723495573d0bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.expand-button','data' => ['chartId' => 'usersMap','title' => 'Active Sessions by Server Location','type' => 'map']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.expand-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chart-id' => 'usersMap','title' => 'Active Sessions by Server Location','type' => 'map']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $attributes = $__attributesOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__attributesOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $component = $__componentOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__componentOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
        </div>
    </div>
    <div class="border-t border-gray-100 p-4 dark:border-gray-800 sm:p-6">
        <div id="usersMap" style="width:100%; height:320px"></div>

        
        <div id="usersMap-extra">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topCountries->isNotEmpty()): ?>
                <div class="mt-5 space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $topCountries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $pct = round(($count / $topCountriesMax) * 100); ?>
                        <div class="flex items-center gap-3">
                            <img src="https://flagcdn.com/w40/<?php echo e(strtolower($code)); ?>.png" alt="<?php echo e($code); ?>" class="h-6 w-8 rounded object-cover">
                            <div class="min-w-[70px]">
                                <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($code); ?></p>
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400"><?php echo e(number_format($count)); ?> connected</p>
                            </div>
                            <div class="flex flex-1 items-center gap-3">
                                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-white/5">
                                    <div class="h-full rounded-full bg-brand-500" style="width:<?php echo e($pct); ?>%"></div>
                                </div>
                                <span class="w-10 shrink-0 text-right text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($pct); ?>%</span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $widgets['users-map'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Active Sessions by Proxy Server</h3>
        <span id="active-servers-total" class="rounded-full bg-brand-50 px-2.5 py-1 text-xs font-medium text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">
            <?php echo e(number_format($proxySummary['sessions']['live'] ?? 0)); ?> online
        </span>
    </div>
    <div id="active-servers-list" class="max-h-[320px] overflow-y-auto border-t border-gray-100 dark:border-gray-800">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $proxyServers->where('live_sessions', '>', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $server): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $pct = round(($server->live_sessions / $maxServerConnected) * 100); ?>
            <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-2.5 dark:border-gray-800">
                <div class="w-5 shrink-0 text-center"><?php if (isset($component)) { $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.flag','data' => ['flag' => $server->flag]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.flag'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flag' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($server->flag)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $attributes = $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $component = $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?></div>
                <div class="min-w-0 flex-1">
                    <div class="mb-1 flex items-center justify-between">
                        <span class="truncate text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($server->name); ?></span>
                        <span class="ml-2 text-theme-sm font-bold text-gray-800 dark:text-white/90"><?php echo e($server->live_sessions); ?></span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-white/5">
                        <div class="h-full rounded-full bg-linear-to-r from-brand-500 to-blue-light-500" style="width:<?php echo e($pct); ?>%"></div>
                    </div>
                    <div class="mt-1 text-theme-xs text-gray-400"><?php echo e($server->country ?? '—'); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="py-10 text-center text-sm text-gray-400">No live connection data yet.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $widgets['ranked-servers'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Server Setup Status</h3>
            <p id="protocol-updated" class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400"><?php echo e($totalProxyServers); ?> proxy servers</p>
        </div>
        <?php if (isset($component)) { $__componentOriginalb508698af2cd78930bc723495573d0bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb508698af2cd78930bc723495573d0bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.expand-button','data' => ['chartId' => 'protocolChart','title' => 'Server Setup Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.expand-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chart-id' => 'protocolChart','title' => 'Server Setup Status']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $attributes = $__attributesOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__attributesOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $component = $__componentOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__componentOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
    </div>
    <div class="border-t border-gray-100 p-2 dark:border-gray-800">
        <div id="protocolChart" style="min-height:260px"></div>
    </div>
</div>
<?php $widgets['vpn-protocols'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Device Platforms</h3>
            <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Active devices by platform</p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(array_sum($devCounts) > 0): ?>
            <?php if (isset($component)) { $__componentOriginalb508698af2cd78930bc723495573d0bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb508698af2cd78930bc723495573d0bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.expand-button','data' => ['chartId' => 'devicePieChart','title' => 'Device Platforms']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.expand-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chart-id' => 'devicePieChart','title' => 'Device Platforms']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $attributes = $__attributesOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__attributesOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $component = $__componentOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__componentOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="flex items-center justify-center border-t border-gray-100 p-2 dark:border-gray-800">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(array_sum($devCounts) > 0): ?>
            <div id="devicePieChart" style="width:100%; min-height:260px"></div>
        <?php else: ?>
            <p class="py-10 text-center text-sm text-gray-400">No active devices.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $widgets['device-platforms'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="px-5 py-4 sm:px-6">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Top Plans by Revenue</h3>
        <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Last 30 days</p>
    </div>
    <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topPlansByRevenue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $pct = $maxPlanRevenue > 0 ? round(($plan->revenue / $maxPlanRevenue) * 100) : 0; ?>
            <div class="mb-4 last:mb-0">
                <div class="mb-1 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex h-5.5 w-5.5 items-center justify-center rounded-full bg-linear-to-br from-brand-500 to-blue-light-500 text-xs font-semibold text-white"><?php echo e($i + 1); ?></span>
                        <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($plan->name); ?></span>
                    </div>
                    <div class="text-right">
                        <span class="text-theme-sm font-bold text-gray-800 dark:text-white/90">$<?php echo e(number_format($plan->revenue ?? 0, 2)); ?></span>
                        <span class="ml-1 text-theme-xs text-gray-400">(<?php echo e($plan->sub_count); ?>)</span>
                    </div>
                </div>
                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-white/5">
                    <div class="h-full rounded-full bg-linear-to-r from-brand-500 to-blue-light-500" style="width:<?php echo e($pct); ?>%"></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="py-6 text-center text-sm text-gray-400">No revenue data yet.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $widgets['top-plans'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Revenue Trend</h3>
            <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Last 30 days</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="rounded-full bg-accent/10 px-2.5 py-1 text-xs font-medium text-accent">
                $<?php echo e(number_format($revenueThisMonth, 2)); ?> this month
            </span>
            <?php if (isset($component)) { $__componentOriginalb508698af2cd78930bc723495573d0bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb508698af2cd78930bc723495573d0bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.expand-button','data' => ['chartId' => 'revenueChart','title' => 'Revenue Trend']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.expand-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chart-id' => 'revenueChart','title' => 'Revenue Trend']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $attributes = $__attributesOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__attributesOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $component = $__componentOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__componentOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
        </div>
    </div>
    <div class="border-t border-gray-100 p-2 dark:border-gray-800">
        <div id="revenueChart" style="min-height:260px"></div>
    </div>
</div>
<?php $widgets['revenue-trend'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Subscription Status</h3>
            <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Distribution by status</p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(array_sum($subValues) > 0): ?>
            <?php if (isset($component)) { $__componentOriginalb508698af2cd78930bc723495573d0bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb508698af2cd78930bc723495573d0bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.expand-button','data' => ['chartId' => 'subscriptionDonut','title' => 'Subscription Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.expand-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chart-id' => 'subscriptionDonut','title' => 'Subscription Status']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $attributes = $__attributesOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__attributesOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb508698af2cd78930bc723495573d0bb)): ?>
<?php $component = $__componentOriginalb508698af2cd78930bc723495573d0bb; ?>
<?php unset($__componentOriginalb508698af2cd78930bc723495573d0bb); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="flex items-center justify-center border-t border-gray-100 p-2 dark:border-gray-800">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(array_sum($subValues) > 0): ?>
            <div id="subscriptionDonut" style="width:100%; min-height:260px"></div>
        <?php else: ?>
            <p class="py-10 text-center text-sm text-gray-400">No subscriptions yet.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $widgets['subscription-status'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Proxy Servers</h3>
        <a href="<?php echo e(route('proxy-servers.all')); ?>" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">View All</a>
    </div>
    <div class="max-h-[420px] overflow-y-auto border-t border-gray-100 dark:border-gray-800">
        <table class="w-full text-left">
            <thead class="sticky top-0 bg-gray-50 dark:bg-gray-900">
                <tr class="text-theme-xs uppercase text-gray-500 dark:text-gray-400">
                    <th class="px-5 py-3 font-medium">Server</th>
                    <th class="px-3 py-3 font-medium">Location</th>
                    <th class="px-3 py-3 text-center font-medium">Setup</th>
                    <th class="px-3 py-3 text-center font-medium">Live Sessions</th>
                    <th class="px-3 py-3 text-center font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $proxyServers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $setupColors = [
                            'pending' => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
                            'installing' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                            'ready' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                            'failed' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
                        ];
                    ?>
                    <tr>
                        <td class="px-5 py-2.5">
                            <div class="text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($ps->name); ?></div>
                            <code class="text-theme-xs text-gray-400"><?php echo e($ps->ip); ?></code>
                        </td>
                        <td class="px-3 py-2.5">
                            <span class="inline-flex items-center gap-1.5 text-theme-sm text-gray-600 dark:text-gray-400">
                                <?php if (isset($component)) { $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tailadmin.flag','data' => ['flag' => $ps->flag]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tailadmin.flag'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flag' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ps->flag)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $attributes = $__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__attributesOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1)): ?>
<?php $component = $__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1; ?>
<?php unset($__componentOriginal00f27caaeee46d1bd3eef4e002d05ff1); ?>
<?php endif; ?> <?php echo e($ps->country ?? '—'); ?>

                            </span>
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold <?php echo e($setupColors[$ps->setup_status] ?? $setupColors['pending']); ?>">
                                <?php echo e(ucfirst($ps->setup_status)); ?>

                            </span>
                        </td>
                        <td class="px-3 py-2.5 text-center text-theme-sm font-medium text-gray-800 dark:text-white/90">
                            <?php echo e($ps->live_sessions); ?>

                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ps->is_active): ?>
                                <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Active</span>
                            <?php else: ?>
                                <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-semibold text-error-600 dark:bg-error-500/15 dark:text-error-400">Inactive</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No proxy servers found.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $widgets['vps-table'] = ob_get_clean(); ?>


<?php ob_start(); ?>
<div class="<?php echo e($cardClass); ?>">
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Signups</h3>
        <a href="<?php echo e(route('users.all')); ?>" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">View All</a>
    </div>
    <div class="border-t border-gray-100 dark:border-gray-800">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $initials = collect(explode(' ', $u->name))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
                $bg = $avatarColors[abs(crc32($u->name)) % count($avatarColors)];
                $sub = $u->activeSubscription;
                if (!$sub) $badge = ['None', 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400'];
                elseif ($sub->status === 'trialing') $badge = ['Trial', 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400'];
                else $badge = ['Active', 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400'];
            ?>
            <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-2.5 last:border-0 dark:border-gray-800">
                <div class="flex h-9.5 w-9.5 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-white"
                    style="background:<?php echo e($bg); ?>">
                    <?php echo e($initials); ?>

                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-theme-sm font-medium text-gray-800 dark:text-white/90"><?php echo e($u->name); ?></div>
                    <div class="truncate text-theme-xs text-gray-400"><?php echo e($u->email); ?></div>
                </div>
                <div class="shrink-0 text-right">
                    <span class="rounded-full px-2 py-0.5 text-xs font-medium <?php echo e($badge[1]); ?>"><?php echo e($badge[0]); ?></span>
                    <div class="mt-1 text-theme-xs text-gray-400"><?php echo e($u->created_at->diffForHumans(short: true)); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="py-10 text-center text-sm text-gray-400">No users yet.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $widgets['recent-signups'] = ob_get_clean(); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isCustomLayout): ?>
    
    <div id="dashboard-grid" class="grid grid-cols-1 gap-4 md:grid-cols-12 md:gap-6" data-custom-layout="1">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $dashboardOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $widgetId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $sizeKey = $widgetSizes[$widgetId] ?? 'sm'; ?>
            <?php echo $wrap($widgetId, 'h-full '.($sizeClasses[$sizeKey] ?? $sizeClasses['sm']), $sizeKey); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php else: ?>
    
    <div id="dashboard-grid" data-custom-layout="0">
        <div class="mb-4 grid grid-cols-1 gap-4 md:gap-6 xl:grid-cols-12 xl:items-stretch">
            <div class="xl:col-span-7">
                <div class="grid h-full grid-cols-1 gap-4 sm:grid-cols-2 sm:auto-rows-fr md:gap-6">
                    <?php echo $wrap('kpi-live-vpn', 'h-full'); ?>

                    <?php echo $wrap('kpi-revenue', 'h-full'); ?>

                    <?php echo $wrap('kpi-total-users', 'h-full'); ?>

                    <?php echo $wrap('kpi-open-tickets', 'h-full'); ?>

                </div>
            </div>
            <div class="xl:col-span-5">
                <?php echo $wrap('vps-health-gauge', 'h-full'); ?>

            </div>
        </div>

        <div class="mb-4 grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 xl:col-span-7"><?php echo $wrap('users-map', 'h-full'); ?></div>
            <div class="col-span-12 xl:col-span-5"><?php echo $wrap('ranked-servers', 'h-full'); ?></div>
        </div>

        <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 md:gap-6">
            <?php echo $wrap('vpn-protocols', 'h-full'); ?>

            <?php echo $wrap('device-platforms', 'h-full'); ?>

            <?php echo $wrap('top-plans', 'h-full'); ?>

        </div>

        <div class="mb-4 grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 xl:col-span-8"><?php echo $wrap('revenue-trend', 'h-full'); ?></div>
            <div class="col-span-12 xl:col-span-4"><?php echo $wrap('subscription-status', 'h-full'); ?></div>
        </div>

        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 xl:col-span-7"><?php echo $wrap('vps-table', 'h-full'); ?></div>
            <div class="col-span-12 xl:col-span-5"><?php echo $wrap('recent-signups', 'h-full'); ?></div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<style>
.blink-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #12b76a;
    border-radius: 50%;
    animation: blink 1.2s infinite;
    vertical-align: middle;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.2; }
}
.kpi-dot {
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
@media (prefers-reduced-motion: reduce) {
    .blink-dot { animation: none; }
}
.dashboard-widget.is-dragging {
    opacity: 0.4;
}
/* Pie/donut tooltips color each row's background to match its series —
   the default black text is hard to read against the darker series colors
   (e.g. the pink "trialing" row), so force it white for just these two
   charts specifically (the area/bar charts' tooltips use a plain light/dark
   box with a colored dot marker instead, and shouldn't be touched). */
#subscriptionDonut .apexcharts-tooltip-text-y-label,
#subscriptionDonut .apexcharts-tooltip-text-y-value,
#devicePieChart .apexcharts-tooltip-text-y-label,
#devicePieChart .apexcharts-tooltip-text-y-value {
    color: #fff;
}
/* jsVectorMap's country-name tooltip is positioned absolutely inside its own
   .jvm-container (which clips overflow) — give it enough stacking priority
   that it never renders invisibly behind/under a neighboring card. */
.jvm-tooltip {
    z-index: 99999;
}
body {
    transition: opacity 180ms ease;
}
body.dashboard-reloading {
    opacity: 0;
}
@media (prefers-reduced-motion: reduce) {
    body { transition: none; }
}
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/jsvectormap/js/jsvectormap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/jsvectormap/js/world.js')); ?>"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('chartModal', { open: false, title: '' });
});

// ── Chart "zoom" modal ────────────────────────────────────────────────
// Rather than moving a chart's DOM node (unreliable with ApexCharts once
// reparented), we render a fresh, larger instance of the same config into
// the modal on open, and tear it down again on close.
window.dashboardChartConfigs = {};
let modalChartInstance = null;
let modalMapInstance = null;

// Some cards show a short summary below the chart itself (e.g. VPS Health's
// Healthy/Degraded/Offline row, or the map's top-countries list). That markup lives in
// a "{chartId}-extra" element next to the original chart — clone it into the modal too.
function appendExtraContent(chartId, modalBody) {
    const extra = document.getElementById(chartId + '-extra');
    if (extra && extra.innerHTML.trim()) {
        const clone = extra.cloneNode(true);
        clone.removeAttribute('id');
        modalBody.appendChild(clone);
    }
}

function zoomChart(chartId, title, type = 'apex') {
    const modalBody = document.getElementById('chart-modal-body');
    if (!modalBody) return;

    modalBody.innerHTML = '';
    Alpine.store('chartModal').title = title;
    Alpine.store('chartModal').open = true;

    if (type === 'map') {
        const mapDiv = document.createElement('div');
        mapDiv.id = 'usersMap-zoom';
        mapDiv.style.width = '100%';
        mapDiv.style.minHeight = '440px';
        mapDiv.style.height = '440px';
        modalBody.appendChild(mapDiv);
        appendExtraContent(chartId, modalBody);

        // jsVectorMap measures its container's actual layout size when it's constructed,
        // so it needs a real paint cycle after the modal becomes visible before we create it
        // (a requestAnimationFrame alone isn't reliably enough of a delay for this).
        setTimeout(() => {
            try {
                modalMapInstance = createUsersMap(countryActiveUsers, '#usersMap-zoom');
            } catch (e) {
                console.error('Failed to render zoomed map:', e);
            }
        }, 60);
        return;
    }

    requestAnimationFrame(() => {
        const cfg = window.dashboardChartConfigs[chartId];
        if (!cfg) return;
        const chartDiv = document.createElement('div');
        modalBody.appendChild(chartDiv);
        modalChartInstance = new ApexCharts(chartDiv, {
            ...cfg,
            chart: { ...cfg.chart, height: 420 },
        });
        modalChartInstance.render();
        appendExtraContent(chartId, modalBody);
    });
}

function closeChartModal() {
    Alpine.store('chartModal').open = false;

    if (modalChartInstance) {
        modalChartInstance.destroy();
        modalChartInstance = null;
    }
    if (modalMapInstance) {
        modalMapInstance.destroy();
        modalMapInstance = null;
    }
    const modalBody = document.getElementById('chart-modal-body');
    if (modalBody) modalBody.innerHTML = '';
}

const isDarkTheme = document.documentElement.classList.contains('dark');
const chartForeColor = isDarkTheme ? 'rgba(255, 255, 255, 0.65)' : '#64748b';
const chartGridColor = isDarkTheme ? 'rgba(255, 255, 255, 0.08)' : '#f1f3fa';
const chartTooltipTheme = isDarkTheme ? 'dark' : 'light';

// ── Proxy Fleet Status semi-circle gauge ─────────────────────────────
const gaugeProxyConfig = {
    chart: { type: 'radialBar', height: 280, offsetY: -10 },
    series: [<?php echo e($proxyHealthPct); ?>],
    plotOptions: {
        radialBar: {
            startAngle: -90,
            endAngle: 90,
            hollow: { size: '68%' },
            track: { background: chartGridColor, strokeWidth: '100%' },
            dataLabels: {
                name: { show: false },
                value: {
                    fontSize: '30px',
                    fontWeight: 700,
                    color: isDarkTheme ? '#fff' : '#1d2939',
                    offsetY: -10,
                    formatter: v => v + '%',
                },
            },
        },
    },
    fill: { colors: ['<?php echo e($proxyHealthPct >= 70 ? "#12b76a" : ($proxyHealthPct >= 40 ? "#f79009" : "#f04438")); ?>'] },
    stroke: { lineCap: 'round' },
};
const gaugeProxyChart = new ApexCharts(document.getElementById('gaugeProxy'), gaugeProxyConfig);
gaugeProxyChart.render();
window.dashboardChartConfigs.gaugeProxy = gaugeProxyConfig;

// ── Revenue Area Chart ──────────────────────────────────────────────
const revenueChartConfig = {
    chart: { type: 'area', height: 260, toolbar: { show: false }, zoom: { enabled: false }, foreColor: chartForeColor },
    series: [{ name: 'Revenue', data: <?php echo json_encode($revenueValues, 15, 512) ?> }],
    xaxis: { categories: <?php echo json_encode($revenueDates, 15, 512) ?>, tickAmount: 6, labels: { style: { fontSize: '11px' } } },
    yaxis: { labels: { formatter: v => '$' + v.toFixed(2) } },
    colors: ['#00bae6'],
    fill: {
        type: 'gradient',
        gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, gradientToColors: ['#85e1f8'] }
    },
    stroke: { curve: 'smooth', width: 2 },
    dataLabels: { enabled: false },
    tooltip: { theme: chartTooltipTheme, y: { formatter: v => '$' + parseFloat(v).toFixed(2) } },
    grid: { borderColor: chartGridColor, strokeDashArray: 4 },
};
const revenueChart = new ApexCharts(document.getElementById('revenueChart'), revenueChartConfig);
revenueChart.render();
window.dashboardChartConfigs.revenueChart = revenueChartConfig;

// ── Subscription Donut ──────────────────────────────────────────────
<?php if(array_sum($subValues) > 0): ?>
const subscriptionDonutConfig = {
    chart: { type: 'donut', height: 260, foreColor: chartForeColor },
    series: <?php echo json_encode($subValues, 15, 512) ?>,
    labels: <?php echo json_encode($subLabels, 15, 512) ?>,
    colors: <?php echo json_encode($subColors, 15, 512) ?>,
    legend: { position: 'bottom', fontSize: '12px' },
    dataLabels: { enabled: false },
    plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', color: chartForeColor, formatter: w => w.globals.seriesTotals.reduce((a,b) => a+b, 0) } } } } },
    tooltip: { theme: chartTooltipTheme, y: { formatter: v => v + ' subscriptions' } },
};
const subscriptionDonut = new ApexCharts(document.getElementById('subscriptionDonut'), subscriptionDonutConfig);
subscriptionDonut.render();
window.dashboardChartConfigs.subscriptionDonut = subscriptionDonutConfig;
<?php endif; ?>

// ── Server Setup Status Bar Chart ────────────────────────────────────
const protocolChartConfig = {
    chart: { type: 'bar', height: 260, toolbar: { show: false }, foreColor: chartForeColor },
    plotOptions: { bar: { horizontal: true, borderRadius: 4, dataLabels: { position: 'top' } } },
    series: [{ name: 'Servers', data: <?php echo json_encode($protocolValues, 15, 512) ?> }],
    xaxis: { categories: <?php echo json_encode($protocolLabels, 15, 512) ?> },
    colors: ['#12b76a', '#f79009', '#98a2b3', '#f04438'],
    dataLabels: { enabled: true, offsetX: 8, style: { fontSize: '12px', colors: [chartForeColor] } },
    grid: { borderColor: chartGridColor, strokeDashArray: 4 },
    tooltip: { theme: chartTooltipTheme, y: { formatter: v => v + ' servers' } },
};
const protocolChartInstance = new ApexCharts(document.getElementById('protocolChart'), protocolChartConfig);
protocolChartInstance.render();
window.dashboardChartConfigs.protocolChart = protocolChartConfig;

// ── Device Platform Pie ─────────────────────────────────────────────
<?php if(array_sum($devCounts) > 0): ?>
const devicePieChartConfig = {
    chart: { type: 'pie', height: 260, foreColor: chartForeColor },
    series: <?php echo json_encode($devCounts, 15, 512) ?>,
    labels: <?php echo json_encode($devPlatforms, 15, 512) ?>,
    colors: ['#465fff', '#f04438', '#12b76a', '#98a2b3'],
    legend: { position: 'bottom', fontSize: '12px' },
    dataLabels: { formatter: (v, opts) => opts.w.globals.labels[opts.seriesIndex] + ': ' + Math.round(v) + '%' },
    tooltip: { theme: chartTooltipTheme, y: { formatter: v => v + ' devices' } },
};
const devicePieChart = new ApexCharts(document.getElementById('devicePieChart'), devicePieChartConfig);
devicePieChart.render();
window.dashboardChartConfigs.devicePieChart = devicePieChartConfig;
<?php endif; ?>

// ── Active Sessions World Map (dot markers, by server location) ───────
let countryActiveUsers = <?php echo json_encode($countryActiveSessions, 15, 512) ?>;
const mapRegionFill = isDarkTheme ? 'rgba(255, 255, 255, 0.08)' : '#e2e8f0';

// Approximate country centroids (ISO 3166-1 alpha-2 → [lat, lng]) used to place one marker per country.
const COUNTRY_COORDS = {
    AD: [42.55,1.58], AE: [23.42,53.85], AF: [33.94,67.71], AG: [17.06,-61.80], AI: [18.22,-63.07],
    AL: [41.15,20.17], AM: [40.07,45.04], AO: [-11.20,17.87], AR: [-38.42,-63.62], AS: [-14.27,-170.13],
    AT: [47.52,14.55], AU: [-25.27,133.78], AW: [12.52,-69.97], AZ: [40.14,47.58], BA: [43.92,17.68],
    BB: [13.19,-59.54], BD: [23.68,90.36], BE: [50.50,4.47], BF: [12.24,-1.56], BG: [42.73,25.49],
    BH: [26.07,50.56], BI: [-3.37,29.92], BJ: [9.31,2.32], BM: [32.32,-64.75], BN: [4.54,114.73],
    BO: [-16.29,-63.59], BR: [-14.24,-51.93], BS: [25.03,-77.40], BT: [27.51,90.43], BW: [-22.33,24.68],
    BY: [53.71,27.95], BZ: [17.19,-88.50], CA: [56.13,-106.35], CD: [-4.04,21.76], CF: [6.61,20.94],
    CG: [-0.23,15.83], CH: [46.82,8.23], CI: [7.54,-5.55], CK: [-21.24,-159.78], CL: [-35.68,-71.54],
    CM: [7.37,12.35], CN: [35.86,104.20], CO: [4.57,-74.30], CR: [9.75,-83.75], CU: [21.52,-77.78],
    CV: [16.00,-24.01], CY: [35.13,33.43], CZ: [49.82,15.47], DE: [51.17,10.45], DJ: [11.83,42.59],
    DK: [56.26,9.50], DM: [15.41,-61.37], DO: [18.74,-70.16], DZ: [28.03,1.66], EC: [-1.83,-78.18],
    EE: [58.60,25.01], EG: [26.82,30.80], ER: [15.18,39.78], ES: [40.46,-3.75], ET: [9.15,40.49],
    FI: [61.92,25.75], FJ: [-17.71,178.07], FM: [7.43,150.55], FR: [46.23,2.21], GA: [-0.80,11.61],
    GB: [55.38,-3.44], GD: [12.26,-61.60], GE: [42.32,43.36], GH: [7.95,-1.02], GL: [71.71,-42.60],
    GM: [13.44,-15.31], GN: [9.95,-9.70], GQ: [1.65,10.27], GR: [39.07,21.82], GT: [15.78,-90.23],
    GW: [11.80,-15.18], GY: [4.86,-58.93], HK: [22.32,114.17], HN: [15.20,-86.24], HR: [45.10,15.20],
    HT: [18.97,-72.29], HU: [47.16,19.50], ID: [-0.79,113.92], IE: [53.41,-8.24], IL: [31.05,34.85],
    IN: [20.59,78.96], IQ: [33.22,43.68], IR: [32.43,53.69], IS: [64.96,-19.02], IT: [41.87,12.57],
    JM: [18.11,-77.30], JO: [30.59,36.24], JP: [36.20,138.25], KE: [-0.02,37.91], KG: [41.20,74.77],
    KH: [12.57,104.99], KI: [1.87,-157.36], KM: [-11.88,43.87], KN: [17.36,-62.78], KP: [40.34,127.51],
    KR: [35.91,127.77], KW: [29.31,47.48], KY: [19.51,-80.57], KZ: [48.02,66.92], LA: [19.86,102.50],
    LB: [33.85,35.86], LC: [13.91,-60.98], LI: [47.17,9.56], LK: [7.87,80.77], LR: [6.43,-9.43],
    LS: [-29.61,28.23], LT: [55.17,23.88], LU: [49.82,6.13], LV: [56.88,24.60], LY: [26.34,17.23],
    MA: [31.79,-7.09], MC: [43.75,7.41], MD: [47.41,28.37], ME: [42.71,19.37], MG: [-18.77,46.87],
    MH: [7.13,171.18], MK: [41.61,21.75], ML: [17.57,-3.99], MM: [21.91,95.96], MN: [46.86,103.85],
    MO: [22.20,113.54], MR: [21.01,-10.94], MT: [35.94,14.38], MU: [-20.35,57.55], MV: [3.20,73.22],
    MW: [-13.25,34.30], MX: [23.63,-102.55], MY: [4.21,101.98], MZ: [-18.67,35.53], NA: [-22.96,18.49],
    NC: [-20.90,165.62], NE: [17.61,8.08], NG: [9.08,8.68], NI: [12.87,-85.21], NL: [52.13,5.29],
    NO: [60.47,8.47], NP: [28.39,84.12], NR: [-0.52,166.93], NZ: [-40.90,174.89], OM: [21.51,55.92],
    PA: [8.54,-80.78], PE: [-9.19,-75.02], PF: [-17.68,-149.41], PG: [-6.31,143.96], PH: [12.88,121.77],
    PK: [30.38,69.35], PL: [51.92,19.15], PR: [18.22,-66.59], PS: [31.95,35.23], PT: [39.40,-8.22],
    PW: [7.51,134.58], PY: [-23.44,-58.44], QA: [25.35,51.18], RO: [45.94,24.97], RS: [44.02,21.01],
    RU: [61.52,105.32], RW: [-1.94,29.87], SA: [23.89,45.08], SB: [-9.65,160.16], SC: [-4.68,55.49],
    SD: [12.86,30.22], SE: [60.13,18.64], SG: [1.35,103.82], SI: [46.15,14.99], SK: [48.67,19.70],
    SL: [8.46,-11.78], SM: [43.94,12.46], SN: [14.50,-14.45], SO: [5.15,46.20], SR: [3.92,-56.03],
    SS: [6.88,31.31], ST: [0.19,6.61], SV: [13.79,-88.90], SY: [34.80,38.997], SZ: [-26.52,31.47],
    TD: [15.45,18.73], TG: [8.62,0.82], TH: [15.87,100.99], TJ: [38.86,71.28], TL: [-8.87,125.73],
    TM: [38.97,59.56], TN: [33.89,9.54], TO: [-21.18,-175.20], TR: [38.96,35.24], TT: [10.69,-61.22],
    TV: [-7.11,177.65], TW: [23.70,120.96], TZ: [-6.37,34.89], UA: [48.38,31.17], UG: [1.37,32.29],
    US: [37.09,-95.71], UY: [-32.52,-55.77], UZ: [41.38,64.59], VC: [12.98,-61.29], VE: [6.42,-66.59],
    VN: [14.06,108.28], VU: [-15.38,166.96], WS: [-13.76,-172.10], YE: [15.55,48.52], ZA: [-30.56,22.94],
    ZM: [-13.13,27.85], ZW: [-19.02,29.15],
};

function buildMapMarkers(data) {
    return Object.entries(data)
        .filter(([code]) => COUNTRY_COORDS[code])
        .map(([code, count]) => ({ code, count, coords: COUNTRY_COORDS[code] }));
}

function createUsersMap(data, target = '#usersMap') {
    const markers = buildMapMarkers(data);
    const maxCount = Math.max(1, ...markers.map(m => m.count));

    const map = new jsVectorMap({
        selector: target,
        map: 'world',
        zoomButtons: false,
        zoomOnScroll: false,
        regionStyle: {
            initial: { fill: mapRegionFill },
        },
        markers: markers.map(m => ({
            name: m.code,
            coords: m.coords,
            style: { initial: { r: 5 + (m.count / maxCount) * 7 } },
        })),
        markerStyle: {
            initial: { fill: '#00bae6', stroke: '#fff', 'stroke-width': 2, fillOpacity: 0.85 },
            hover: { fill: '#0098bd' },
        },
        onMarkerTooltipShow(event, tooltip, index) {
            const m = markers[index];
            tooltip.text(tooltip.text() + ': <b>' + (m?.count ?? 0) + ' connected</b>', true);
        },
    });
    map.__markers = markers;

    return map;
}

let usersMap = createUsersMap(countryActiveUsers);

// Fades the page out before reloading, used only by the reset button —
// resetting reverts to the original nested layout markup, which isn't
// present client-side once a custom layout has been loaded, so that one
// genuinely needs a fresh render from the server.
function reloadWithFade() {
    document.body.classList.add('dashboard-reloading');
    setTimeout(() => window.location.reload(), 180);
}

// ── Drag-and-drop widget reordering (native HTML5 DnD, handle-only) ───
// The grip icon in each widget's top-left corner is the only drag trigger:
// `draggable` stays false until the handle is pressed, so clicking buttons,
// links, or chart tooltips inside a card never starts a drag by accident.
//
// Both reordering and resizing (S/M/L/Full) are applied straight to the DOM
// and saved in the background via fetch — neither ever reloads the page.
//
// A drop never moves any grid slot's wrapper element (which is what carries
// its width/position) — it only swaps the *content* (and identity) of the
// dragged and dropped-on slots. Every other widget's wrapper is completely
// untouched, so the CSS grid's auto-placement never has anything else to
// reflow: only the two involved cards visibly change.
//
// The one exception is the very first drag on the default (never-customized)
// nested layout: since that layout's markup isn't a flat set of same-level
// slots yet, that single drop first flattens the grid in place (natural
// order, default sizes) before the same content-swap is applied — still
// without a page reload.
(function initDashboardDragDrop() {
    const grid = document.getElementById('dashboard-grid');
    if (!grid) return;

    let dragSrc = null;
    let isCustomLayout = grid.dataset.customLayout === '1';

    const sizeClasses = <?php echo json_encode($sizeClasses, 15, 512) ?>;
    const sizeLabels = <?php echo json_encode($sizeLabels, 15, 512) ?>;
    const defaultSizes = <?php echo json_encode(\App\Http\Controllers\Admin\DashboardController::DEFAULT_WIDGET_SIZES, 15, 512) ?>;

    grid.querySelectorAll('.drag-handle').forEach((handle) => {
        handle.addEventListener('mousedown', () => {
            handle.closest('.dashboard-widget')?.setAttribute('draggable', 'true');
        });
    });

    grid.addEventListener('dragstart', (e) => {
        const widget = e.target.closest('.dashboard-widget');
        if (!widget || widget.getAttribute('draggable') !== 'true') return;
        dragSrc = widget;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', widget.dataset.widgetId);
        widget.classList.add('is-dragging');
    });

    grid.addEventListener('dragover', (e) => {
        if (!dragSrc) return;
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    });

    grid.addEventListener('drop', (e) => {
        e.preventDefault();
        if (!dragSrc) return;
        const target = e.target.closest('.dashboard-widget');
        if (!target || target === dragSrc) return;

        if (!isCustomLayout) {
            // First-ever drag on the nested default layout: establish the
            // flat grid's slots (natural order, default sizes) first — this
            // one-time switch is the only point where other widgets move.
            flattenInPlace();
        }

        // Swap what the two slots display, not the slots themselves — every
        // other widget's wrapper (its width/position) is left untouched.
        swapContent(dragSrc, target);

        saveDashboardOrder(currentOrder(), currentSizes());
    });

    grid.addEventListener('dragend', () => {
        if (dragSrc) {
            dragSrc.classList.remove('is-dragging');
            dragSrc.setAttribute('draggable', 'false');
            dragSrc = null;
        }
    });

    // Size picker (S/M/L/Full) — only present in the flat/custom layout.
    // Clicking a size button just swaps that one widget's own width classes
    // and saves in the background, no reload.
    grid.addEventListener('click', (e) => {
        const sizeBtn = e.target.closest('.widget-size-btn');
        if (!sizeBtn) return;
        const widget = sizeBtn.closest('.dashboard-widget');
        if (!widget) return;

        applySize(widget, sizeBtn.dataset.size);
        saveDashboardOrder(currentOrder(), currentSizes());
    });

    function isSlotChrome(el) {
        return el.classList.contains('drag-handle') || el.classList.contains('widget-size-picker');
    }

    // Exchanges the actual widget content (and id/identity) between two
    // slots, leaving each slot's own wrapper — its size classes, position in
    // the DOM, drag handle and size picker — exactly where it was. Because
    // no wrapper ever moves or resizes, nothing else in the grid reflows.
    function swapContent(a, b) {
        const aId = a.dataset.widgetId;
        const bId = b.dataset.widgetId;

        const aContent = [...a.children].filter((el) => !isSlotChrome(el));
        const bContent = [...b.children].filter((el) => !isSlotChrome(el));

        const holder = document.createDocumentFragment();
        aContent.forEach((el) => holder.appendChild(el));
        bContent.forEach((el) => a.appendChild(el));
        while (holder.firstChild) {
            b.appendChild(holder.firstChild);
        }

        a.dataset.widgetId = bId;
        b.dataset.widgetId = aId;

        a.querySelector('.widget-size-picker')?.setAttribute('aria-label', 'Resize ' + bId);
        b.querySelector('.widget-size-picker')?.setAttribute('aria-label', 'Resize ' + aId);
    }

    function applySize(widget, newSize) {
        const oldSize = widget.dataset.widgetSize;
        if (newSize === oldSize) return;

        if (sizeClasses[oldSize]) {
            widget.classList.remove(...sizeClasses[oldSize].split(' '));
        }
        widget.classList.add(...sizeClasses[newSize].split(' '));
        widget.dataset.widgetSize = newSize;

        widget.querySelectorAll('.widget-size-btn').forEach((btn) => {
            const isActive = btn.dataset.size === newSize;
            btn.classList.toggle('bg-accent', isActive);
            btn.classList.toggle('text-white', isActive);
            btn.classList.toggle('text-gray-500', !isActive);
            btn.classList.toggle('hover:bg-gray-100', !isActive);
            btn.classList.toggle('dark:text-gray-400', !isActive);
            btn.classList.toggle('dark:hover:bg-white/10', !isActive);
        });
    }

    function buildSizePicker(widgetId, currentKey) {
        const picker = document.createElement('div');
        picker.className = 'widget-size-picker absolute -right-2 -top-2 z-20 flex items-center gap-0.5 rounded-full border border-gray-200 bg-white p-1 opacity-0 shadow-theme-xs transition-opacity pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto dark:border-gray-700 dark:bg-gray-800';
        picker.setAttribute('aria-label', 'Resize ' + widgetId);

        Object.keys(sizeLabels).forEach((key) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            const isActive = key === currentKey;
            btn.className = 'widget-size-btn rounded px-1.5 py-0.5 text-[10px] font-semibold leading-none transition-colors'
                + (isActive ? ' bg-accent text-white' : ' text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/10');
            btn.dataset.size = key;
            btn.textContent = sizeLabels[key];
            picker.appendChild(btn);
        });

        return picker;
    }

    // Detaches every widget from the nested default layout's containers and
    // re-attaches them as direct siblings in one flat 12-col grid, in their
    // current (natural, unswapped) order — mirroring what the server renders
    // for a custom layout, done entirely client-side so no reload is needed.
    // The actual drag's swap is applied separately, after this, via
    // swapContent — so this one-time flatten is the only step that can move
    // more than the two widgets involved in the drag.
    function flattenInPlace() {
        const widgetEls = currentOrder().map((id) => grid.querySelector('[data-widget-id="' + id + '"]'));

        grid.className = 'grid grid-cols-1 gap-4 md:grid-cols-12 md:gap-6';
        grid.innerHTML = '';

        widgetEls.forEach((el) => {
            if (!el) return;
            const id = el.dataset.widgetId;
            const sizeKey = defaultSizes[id] || 'sm';

            el.className = 'dashboard-widget group relative h-full ' + (sizeClasses[sizeKey] || sizeClasses.sm);
            el.dataset.widgetSize = sizeKey;
            el.setAttribute('draggable', 'false');
            if (!el.querySelector('.widget-size-picker')) {
                el.appendChild(buildSizePicker(id, sizeKey));
            }

            grid.appendChild(el);
        });

        isCustomLayout = true;
        grid.dataset.customLayout = '1';
    }

    function currentOrder() {
        return [...grid.querySelectorAll('.dashboard-widget')].map((el) => el.dataset.widgetId);
    }

    function currentSizes() {
        const sizes = {};
        grid.querySelectorAll('.dashboard-widget[data-widget-size]').forEach((el) => {
            sizes[el.dataset.widgetId] = el.dataset.widgetSize;
        });

        return sizes;
    }

    function saveDashboardOrder(widgets, sizes) {
        fetch('<?php echo e(route('admin.dashboard.layout.save')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ widgets, sizes }),
        }).catch(() => {});
    }

    window.addEventListener('dashboard:reset-layout-requested', () => {
        fetch('<?php echo e(route('admin.dashboard.layout.reset')); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        }).then(reloadWithFade).catch(reloadWithFade);
    });
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tailadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>