@section('title', 'My Profile')
<div>
    <x-tailadmin.breadcrumb :items="[['label' => 'My Profile']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';

        $initials = collect(explode(' ', Auth::user()->name))->take(2)->map(fn ($w) => strtoupper($w[0] ?? ''))->implode('');
        $avatarColors = ['#4361ee', '#3bc9db', '#37b24d', '#f03e3e', '#f59f00', '#7950f2', '#1c7ed6', '#e64980'];
        $avatarBg = $avatarColors[abs(crc32(Auth::user()->name)) % count($avatarColors)];
    @endphp

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Left: Avatar + Info card --}}
        <div class="{{ $cardClass }} p-6 text-center xl:col-span-1">
            <div class="mx-auto mb-3 flex h-20 w-20 items-center justify-center rounded-full text-2xl font-bold text-white"
                style="background: {{ $avatarBg }};">
                {{ $initials }}
            </div>
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ Auth::user()->name }}</h5>
            <p class="mb-2 text-theme-sm text-gray-400">{{ Auth::user()->email }}</p>
            <span class="rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold capitalize text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">{{ Auth::user()->role }}</span>

            <div class="mt-5 space-y-0 divide-y divide-gray-100 border-t border-gray-100 text-left dark:divide-gray-800 dark:border-gray-800">
                <div class="flex items-center justify-between py-2 text-theme-sm">
                    <span class="text-gray-400">Member since</span>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between py-2 text-theme-sm">
                    <span class="text-gray-400">Last login</span>
                    <span class="font-medium text-gray-700 dark:text-gray-300">
                        {{ Auth::user()->last_login ? Auth::user()->last_login->diffForHumans() : '—' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 text-theme-sm">
                    <span class="text-gray-400">Email verified</span>
                    @if (Auth::user()->email_verified_at)
                        <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">Yes</span>
                    @else
                        <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-semibold text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">No</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Forms --}}
        <div class="space-y-6 xl:col-span-2">
            {{-- Profile Info --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center gap-1.5 px-5 py-4 sm:px-6">
                    <iconify-icon icon="ri:user-line" width="18" height="18"></iconify-icon>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Profile Information</h3>
                </div>
                <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                    <form wire:submit.prevent="saveProfile" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="{{ $labelClass }}">Name</label>
                                <input type="text" wire:model="name" placeholder="Your full name" class="{{ $inputClass }}">
                                @error('name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Email Address</label>
                                <input type="email" wire:model="email" placeholder="your@email.com" class="{{ $inputClass }}">
                                @error('email') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                                @if (! Auth::user()->email_verified_at)
                                    <p class="mt-1 flex items-center gap-1 text-theme-xs text-warning-600 dark:text-warning-400">
                                        <iconify-icon icon="mdi:alert-circle-outline" width="14" height="14"></iconify-icon>
                                        Email not verified.
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" wire:loading.attr="disabled"
                                class="rounded-lg bg-accent px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-accent/90 disabled:opacity-50">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center gap-1.5 px-5 py-4 sm:px-6">
                    <iconify-icon icon="mdi:lock-outline" width="18" height="18"></iconify-icon>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Change Password</h3>
                </div>
                <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                    <form wire:submit.prevent="changePassword" class="space-y-4">
                        <div>
                            <label class="{{ $labelClass }}">Current Password</label>
                            <input type="password" wire:model="currentPassword" placeholder="Enter your current password" class="{{ $inputClass }}">
                            @error('currentPassword') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="{{ $labelClass }}">New Password</label>
                                <input type="password" wire:model="newPassword" placeholder="Min 8 chars, mixed case + number" class="{{ $inputClass }}">
                                @error('newPassword') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Confirm New Password</label>
                                <input type="password" wire:model="newPasswordConfirmation" placeholder="Repeat new password" class="{{ $inputClass }}">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" wire:loading.attr="disabled"
                                class="rounded-lg bg-error-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-error-600 disabled:opacity-50">
                                <span wire:loading.remove>Update Password</span>
                                <span wire:loading>Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('sweetAlert', (event) => {
        Swal.fire({ title: event.title, text: event.message, icon: event.type, timer: 2500, showConfirmButton: false });
    });
</script>
@endscript
