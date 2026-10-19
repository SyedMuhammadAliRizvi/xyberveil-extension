<?php
    $menuGroups = [
        [
            'title' => null,
            'items' => [
                ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fa-solid fa-house'],
            ],
        ],
        [
            'title' => 'Applications',
            'items' => [
                ['name' => 'Proxy Servers', 'route' => 'proxy-servers.all', 'icon' => 'fa-solid fa-server'],
                ['name' => 'Plans', 'route' => 'plans.all', 'icon' => 'fa-solid fa-dollar-sign'],
                ['name' => 'Users', 'route' => 'users.all', 'icon' => 'fa-solid fa-users'],
                ['name' => 'Online Users', 'route' => 'online-users.monitor', 'icon' => 'fa-solid fa-tower-broadcast'],
            ],
        ],
        [
            'title' => 'Panel',
            'items' => [
                ['name' => 'Admins', 'route' => 'admins.all', 'icon' => 'fa-solid fa-user-shield'],
                ['name' => 'Notification', 'route' => 'admin.notifications', 'icon' => 'fa-regular fa-bell'],
                ['name' => 'Ticket Support', 'route' => 'admin.tickets', 'icon' => 'fa-solid fa-headset'],
                ['name' => 'Feedbacks', 'route' => 'admin.feedbacks', 'icon' => 'fa-solid fa-comment-dots'],
                [
                    'name' => 'Settings',
                    'icon' => 'fa-solid fa-gear',
                    'submenu' => [
                        ['name' => 'Configuration', 'route' => 'all-config', 'icon' => 'fa-solid fa-sliders'],
                    ],
                ],
            ],
        ],
    ];

    $isItemActive = fn ($item) => isset($item['route']) && request()->routeIs($item['active'] ?? $item['route']);

    // Which group-item key (if any) holds the currently active submenu, so it auto-opens.
    $openSubmenuKey = '';
    foreach ($menuGroups as $groupIndex => $group) {
        foreach ($group['items'] as $itemIndex => $item) {
            if (isset($item['submenu']) && collect($item['submenu'])->contains($isItemActive)) {
                $openSubmenuKey = $groupIndex.'-'.$itemIndex;
            }
        }
    }
?>

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200 dark:border-gray-800"
    x-data="{
        openSubmenus: {},
        toggleSubmenu(key) {
            const next = !this.openSubmenus[key];
            this.openSubmenus = {};
            this.openSubmenus[key] = next;
        },
        isSubmenuOpen(key) {
            return !!this.openSubmenus[key];
        },
    }"
    <?php if($openSubmenuKey): ?> x-init="openSubmenus['<?php echo e($openSubmenuKey); ?>'] = true" <?php endif; ?>
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen,
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    
    <div class="pt-8 pb-7 flex items-center gap-2"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2">
            <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="<?php echo e(config('app.name')); ?>" class="h-8 w-8 shrink-0">
            <span class="text-lg font-semibold text-gray-800 dark:text-white/90"
                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                <?php echo e(config('app.name')); ?>

            </span>
        </a>
    </div>

    
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $menuGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group['title']): ?>
                            <h2 class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                    <?php echo e($group['title']); ?>

                                </span>
                            </h2>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <ul class="flex flex-col gap-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $key = $groupIndex . '-' . $itemIndex; ?>
                                <li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($item['submenu'])): ?>
                                        <?php $submenuActive = collect($item['submenu'])->contains($isItemActive); ?>
                                        <button type="button" @click="toggleSubmenu('<?php echo e($key); ?>')"
                                            class="menu-item group w-full <?php echo e($submenuActive ? 'menu-item-active' : 'menu-item-inactive'); ?>"
                                            :class="!$store.sidebar.isExpanded && !$store.sidebar.isHovered ? 'xl:justify-center' : 'xl:justify-start'">
                                            <i class="<?php echo e($item['icon']); ?> shrink-0 w-5 text-center text-[18px] <?php echo e($submenuActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive'); ?>"></i>
                                            <span class="flex-1 text-left"
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                                <?php echo e($item['name']); ?>

                                            </span>
                                            <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="w-5 h-5 transition-transform duration-200"
                                                :class="{ 'rotate-180 text-brand-500': isSubmenuOpen('<?php echo e($key); ?>') }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div x-show="isSubmenuOpen('<?php echo e($key); ?>') && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)">
                                            <ul class="mt-2 space-y-1 ml-9">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item['submenu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <a href="<?php echo e(route($sub['route'])); ?>"
                                                            class="menu-dropdown-item <?php echo e($isItemActive($sub) ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'); ?>">
                                                            <?php echo e($sub['name']); ?>

                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <?php $active = $isItemActive($item); ?>
                                        <a href="<?php echo e(route($item['route'])); ?>"
                                            class="menu-item group <?php echo e($active ? 'menu-item-active' : 'menu-item-inactive'); ?>"
                                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
                                            <i class="<?php echo e($item['icon']); ?> shrink-0 w-5 text-center text-[18px] <?php echo e($active ? 'menu-item-icon-active' : 'menu-item-icon-inactive'); ?>"></i>
                                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                                <?php echo e($item['name']); ?>

                                            </span>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </nav>
    </div>
</aside>
<?php /**PATH C:\xampp\htdocs\xyberVeil_Extension-Admin-Panel\resources\views/layouts/tailadmin-sidebar.blade.php ENDPATH**/ ?>