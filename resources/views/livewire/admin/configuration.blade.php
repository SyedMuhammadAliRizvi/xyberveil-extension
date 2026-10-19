@section('title', 'Configuration')
@section('styles')
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
@endsection
<div>
    <x-tailadmin.breadcrumb :items="[['label' => 'Configuration']]" />

    @php
        $cardClass = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]';
        $inputClass = 'h-11 w-full rounded-lg border border-gray-200 bg-transparent px-3 text-theme-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90';
        $labelClass = 'mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass = 'mt-1 text-theme-xs text-error-500';
    @endphp

    <div class="mb-5 flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800">
        <button type="button" wire:click="$set('navTab', 'email')"
            class="border-b-2 px-4 py-3 text-theme-sm font-medium transition-colors {{ $navTab === 'email' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
            Email
        </button>
        <button type="button" wire:click="$set('navTab', 'tos_privacy')"
            class="border-b-2 px-4 py-3 text-theme-sm font-medium transition-colors {{ $navTab === 'tos_privacy' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
            ToS & Privacy
        </button>
        <button type="button" wire:click="$set('navTab', 'monitoring')"
            class="border-b-2 px-4 py-3 text-theme-sm font-medium transition-colors {{ $navTab === 'monitoring' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
            Monitoring API
        </button>
    </div>

    @if ($navTab === 'email')
        <div class="{{ $cardClass }} max-w-2xl">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Email Configuration</h3>
            </div>
            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                <form wire:submit.prevent="emailConfig" class="space-y-4">
                    <div>
                        <label class="{{ $labelClass }}">SMTP Host</label>
                        <input type="text" placeholder="Enter SMTP Host" wire:model="host" class="{{ $inputClass }}">
                        @error('host') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">SMTP Port</label>
                        <input type="text" placeholder="Enter SMTP Port" wire:model="port" class="{{ $inputClass }}">
                        @error('port') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">SMTP Username</label>
                        <input type="text" placeholder="Enter SMTP Username" wire:model.live="username" class="{{ $inputClass }}">
                        @error('username') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div x-data="{ show: false }">
                        <label class="{{ $labelClass }}">SMTP Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" placeholder="Enter your password" wire:model="password" class="{{ $inputClass }} pr-11">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <iconify-icon :icon="show ? 'fluent:eye-off-20-regular' : 'fluent:eye-20-regular'" width="18" height="18"></iconify-icon>
                            </button>
                        </div>
                        @error('password') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">SMTP Address</label>
                        <input type="text" placeholder="Enter SMTP Address" wire:model="address" class="{{ $inputClass }}">
                        @error('address') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">SMTP Name</label>
                        <input type="text" placeholder="Enter SMTP Name" wire:model="name" class="{{ $inputClass }}">
                        @error('name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
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
    @elseif ($navTab === 'tos_privacy')
        <div class="{{ $cardClass }}">
            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">ToS & Privacy Configuration</h3>
            </div>
            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                <form wire:submit.prevent="policyConfig" class="space-y-4">
                    <div>
                        <label class="{{ $labelClass }}">Refund Policy</label>
                        <livewire:quill-text-editor wire:model.live="refund_policy" theme="snow" />
                        @error('refund_policy') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Terms Of Service</label>
                        <livewire:quill-text-editor wire:model.live="tos" theme="snow" />
                        @error('tos') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Privacy Policy</label>
                        <livewire:quill-text-editor wire:model.live="privacy_policy" theme="snow" />
                        @error('privacy_policy') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
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
    @elseif ($navTab === 'monitoring')
        <div class="{{ $cardClass }} max-w-3xl">
            <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Monitoring API Configuration</h3>
                <span class="rounded-full bg-blue-light-50 px-2.5 py-1 text-xs font-semibold text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400">Online Users Access</span>
            </div>
            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                @if (! $monitor_api_token)
                    <div class="mb-4 rounded-lg border border-blue-light-200 bg-blue-light-50 px-4 py-3 text-theme-sm text-blue-light-700 dark:border-blue-light-500/30 dark:bg-blue-light-500/10 dark:text-blue-light-400">
                        <iconify-icon icon="mdi:information" width="18" height="18" class="mr-1 align-text-bottom"></iconify-icon>
                        No monitoring API token exists. Generate one to let the external Online Users dashboard read live proxy data.
                    </div>

                    <button type="button" wire:click="generateMonitoringToken"
                        class="flex items-center gap-1.5 rounded-lg bg-success-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-success-600">
                        <iconify-icon icon="mdi:key-plus" width="18" height="18"></iconify-icon>
                        Generate API Token
                    </button>
                @else
                    <div class="mb-4">
                        <label class="{{ $labelClass }}">Current API Token</label>
                        <div class="flex gap-2">
                            <input type="text" value="{{ $monitor_api_token }}" readonly class="{{ $inputClass }} font-mono opacity-70">
                            <button type="button" wire:click="regenerateMonitoringToken"
                                onclick="return confirm('This will invalidate the current token. Continue?')"
                                class="flex h-11 shrink-0 items-center gap-1.5 rounded-lg border border-brand-200 px-3 text-theme-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400 dark:hover:bg-brand-500/10">
                                <iconify-icon icon="mdi:refresh" width="16" height="16"></iconify-icon>
                                Regenerate
                            </button>
                        </div>
                        <p class="mt-1 text-theme-xs text-gray-400">Use this token for the online-users monitoring API authentication</p>
                    </div>

                    <div class="mb-4 rounded-lg border border-warning-200 bg-warning-50 px-4 py-3 text-theme-sm text-warning-700 dark:border-warning-500/30 dark:bg-warning-500/10 dark:text-warning-400">
                        <iconify-icon icon="mdi:security" width="18" height="18" class="mr-1 align-text-bottom"></iconify-icon>
                        <strong>Security Notice:</strong> Keep this token secure. Anyone with this token can read live proxy session data (user emails, IPs, connection times).
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-4 text-theme-sm text-gray-600 dark:border-gray-800 dark:bg-white/3 dark:text-gray-400">
                        <h6 class="mb-2 flex items-center gap-1.5 font-semibold text-gray-800 dark:text-white/90">
                            <iconify-icon icon="mdi:api" width="18" height="18"></iconify-icon>
                            API Usage Information
                        </h6>
                        <p class="mb-1"><strong class="text-gray-700 dark:text-gray-300">Base URL:</strong> <code>{{ config('app.url') }}/api/vps</code></p>
                        <p class="mb-2"><strong class="text-gray-700 dark:text-gray-300">Authentication:</strong> Include header <code>Authorization: Bearer {{ $monitor_api_token }}</code></p>
                        <p class="mb-1"><strong class="text-gray-700 dark:text-gray-300">Available Endpoints:</strong></p>
                        <ul class="mt-1 list-disc space-y-1 pl-5">
                            <li><code>GET /online-users</code> - Live proxy sessions and fleet stats, for the external Online Users dashboard</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="{{ $cardClass }} p-5 sm:p-6">
            <h5 class="text-base font-semibold text-gray-800 dark:text-white/90">Default Configuration</h5>
        </div>
    @endif
</div>
@section('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endsection
@script
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
@endscript
